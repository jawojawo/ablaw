<?php

namespace App\Http\Controllers;

use App\Models\AdminDeposit;
use App\Models\AdministrativeFee;
use App\Models\AdministrativeFeeCategory;
use App\Models\Billing;
use App\Models\Client;
use App\Models\Deposit;
use App\Models\Hearing;
use App\Models\LawCase;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Barryvdh\Snappy\Facades\SnappyPdf;

class LawCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view', LawCase::class);
        $search = $request->input('search');
        $caseTypes = $request->input('case_type', []);
        $partyRoles = $request->input('party_role', []);
        $opposingParty = $request->input('opposing_party');
        $status = $request->input('status', []);
        $bills = $request->input('bills', []);
        $cases = LawCase::query()->with(['client', 'associates', 'hearings', 'billings'])
            ->withCount(['notes', 'billings', 'overDueBills', 'upcomingBills', 'dueTodayBills', 'paidBills'])
            ->when($search, function ($query, $search) {
                $query->where('case_number', 'like', "%{$search}%")->orWhere('case_title', 'like', "%{$search}%");
            })
            ->when($caseTypes, function ($query, $caseTypes) {
                $query->whereNotIn('case_type', $caseTypes);
            })
            ->when($partyRoles, function ($query, $partyRoles) {
                $query->whereNotIn('party_role', $partyRoles);
            })
            ->when($opposingParty, function ($query, $opposingParty) {
                $query->where('opposing_party', 'like', "%{$opposingParty}%");
            })
            ->when($status, function ($query, $status) {
                $query->whereNotIn('status', $status);
            })->when($bills, function ($query) use ($bills) {
                $query->where(function ($query) use ($bills) {
                    if (in_array('overDue', $bills)) {
                        $query->whereDoesntHave('overDueBills');
                    }
                    if (in_array('upcoming', $bills)) {
                        $query->whereDoesntHave('upcomingBills');
                    }
                    if (in_array('dueToday', $bills)) {
                        $query->whereDoesntHave('dueTodayBills');
                    }
                    if (in_array('paid', $bills)) {
                        $query->whereDoesntHave('paidBills');
                    }
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('case.index', ['cases' => $cases]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('create', LawCase::class);
        $client = Client::find($request->get('client_id'));

        return view('case.create', ['client' => $client]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', LawCase::class);
        $validatedData = $this->validateCase($request);
        $validatedAdminDeposits = $this->validateAdminDeposit($request);
        $validatedAdminFees = $this->validateAdminFee($request);
        $validatedHearings = $this->validateHearing($request);
        $validatedBillings = $this->validateBilling($request);
        $validatedContacts = $this->validateContacts($request);
        $lawCase = null;

        try {
            DB::beginTransaction();
            $lawCase = LawCase::create($validatedData);
            if (!$lawCase) {
                throw new \Exception('Law case creation failed.');
            }
            if (!empty($validatedAdminDeposits)) {
                $lawCase->adminDeposits()->createMany($validatedAdminDeposits);
            }

            if (!empty($validatedAdminFees)) {
                $lawCase->adminFees()->createMany($validatedAdminFees);
            }

            if (!empty($validatedHearings)) {
                $lawCase->hearings()->createMany($validatedHearings);
            }

            if (!empty($validatedBillings)) {
                $lawCase->billings()->createMany($validatedBillings);
            }
            if (!empty($validatedContacts)) {
                $lawCase->contacts()->createMany($validatedContacts);
            }
            DB::commit();

            return redirect()->route('case.show', $lawCase->id)->with('success', 'Case created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e->getMessage());
            return redirect()->back()->withErrors('Error creating case.');
        }
    }

    public function show(LawCase $lawCase, Request $request)
    {
        $this->authorize('view', LawCase::class);
        if ($request->ajax()) {
            switch ($request->table) {
                case  "admin-deposits":
                    $this->authorize('view', AdminDeposit::class);
                    return view('components.admin-deposit.admin-deposits-table', ['adminDeposits' => $lawCase->adminDeposits()])->render();
                    break;
                case  "admin-fees":
                    $this->authorize('view', AdministrativeFee::class);
                    return view('components.admin-fee.admin-fees-table', ['adminFees' => $lawCase->adminFees()])->render();
                    break;
                case  "hearings":
                    $this->authorize('view', Hearing::class);
                    return view('components.hearing.hearings-table', ['hearings' => $lawCase->hearings()])->render();
                    break;
                case  "billings":
                    $this->authorize('view', Billing::class);
                    return view('components.billing.billings-table', ['billings' => $lawCase->billings()->orderedBillings()])->render();
                    break;
                case  "extra-info":
                    $lawCase->loadCount(['upcomingHearings', 'ongoingHearings', 'completedHearings', 'canceledHearings']);
                    $lawCase->loadCount(['overDueBills', 'upcomingBills', 'dueTodayBills', 'paidBills']);
                    return view('components.extra-info', ['lawCase' => $lawCase])->render();
                    break;
                case  "billing-deposits":
                    $this->authorize('view', Deposit::class);
                    $billing = Billing::find($request->id);
                    $billing->loadCount(['notes']);
                    return view('components.billing-deposit.billing-deposits-table', ['billing' => $billing])->render();
                    break;
                case  "notes":
                    return view('components.note.notes-table', ['notes' => $lawCase->notes()])->render();
                    break;
                default:
                    return '';
                    break;
            }
        }

        if ($request->pdf) {
            $lawCase->loadCount(['upcomingHearings', 'ongoingHearings', 'completedHearings', 'canceledHearings']);
            $lawCase->loadCount(['overDueBills', 'upcomingBills', 'dueTodayBills', 'paidBills']);
            // return SnappyPdf::loadView('case.pdf-view', ['lawCase' => $lawCase]);
            //  return view('case.pdf-view', ['lawCase' => $lawCase]);
            return SnappyPdf::loadView('case.pdf-view', ['lawCase' => $lawCase])->inline();
            // switch ($request->pdf) {
            //     case 'view':
            //              return Pdf::view('case.pdf-view', ['lawCase' => $lawCase])->margins(10, 10, 10, 10)->footerView('pdf.footer');
            //         break;
            //     case 'download':
            //         return Pdf::view('case.pdf-view', ['lawCase' => $lawCase])->margins(10, 10, 10, 10)->footerView('pdf.footer')->download($lawCase->case_number . '.pdf');
            // }
        }
        $lawCase->loadCount(['notes']);


        return view('case.show', [
            'lawCase' => $lawCase,


        ]);
    }
    public function addAdminDeposit(LawCase $lawCase, Request $request)
    {
        $this->authorize('create', AdminDeposit::class);
        $paymentTypes = config('enums.payment_types');
        $validated = $request->validate([
            'payment_type' => ['required', 'string', Rule::in($paymentTypes)],
            'amount' => 'required|numeric|min:1',
            'deposit_date' => 'required|date',
        ]);

        $lawCase->adminDeposits()->create($validated);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Admin Deposit added successfully!',
            ]);
        }
    }
    public function updateAdminDeposit(AdminDeposit $adminDeposit, Request $request)
    {
        $this->authorize('update', AdminDeposit::class);
        $paymentTypes = config('enums.payment_types');
        $validated = $request->validate([
            'payment_type' => ['required', 'string', Rule::in($paymentTypes)],
            'amount' => 'required|numeric|min:1',
            'deposit_date' => 'required|date',
        ]);
        $adminDeposit->update($validated);
        return response()->json([
            'success' => true,
            'message' => "#$adminDeposit->id Admin deposit updated successfully!",

        ]);
    }
    public function deleteAdminDeposit(AdminDeposit $adminDeposit)
    {
        $this->authorize('delete', AdminDeposit::class);
        $id = $adminDeposit->id;
        $adminDeposit->delete();

        return response()->json([
            'success' => true,
            'message' => "#$id Admin deposit deleted successfully!"
        ]);
    }

    public function addAdminFee(LawCase $lawCase, Request $request)
    {
        $this->authorize('create', AdministrativeFee::class);
        $validated = $request->validate([
            'type' => 'required|string',
            // 'administrative_fee_category_id' => 'required|exists:administrative_fee_categories,id',
            'amount' => 'required|numeric|min:1',
            'fee_date'  => 'date|required',
        ]);
        $lawCase->adminFees()->create($validated);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Admin Fee added successfully!',
            ]);
        }
    }
    public function updateAdminFee(AdministrativeFee $adminFee, Request $request)
    {
        $this->authorize('update', AdministrativeFee::class);
        $validated = $request->validate([
            'type' => 'required|string',
            // 'administrative_fee_category_id' => 'required|exists:administrative_fee_categories,id',
            'amount' => 'required|numeric|min:1',
            'fee_date'  => 'date|required',
        ]);
        $adminFee->update($validated);
        return response()->json([
            'success' => true,
            'message' => "#$adminFee->id Admin Fee updated successfully!",
        ]);
    }
    public function deleteAdminFee(AdministrativeFee $adminFee)
    {
        $this->authorize('delete', AdministrativeFee::class);
        $id = $adminFee->id;
        $adminFee->delete();

        return response()->json([
            'success' => true,
            'message' => "#$id Admin Fee deleted successfully!"
        ]);
    }

    public function addHearing(LawCase $lawCase, Request $request)
    {
        $this->authorize('create', Hearing::class);
        $validated =   $request->validate([
            'title' => 'required|string',
            'court_branch_id'  => 'required||exists:court_branches,id',
            'hearing_date' => 'date|required',
        ]);
        $lawCase->hearings()->create($validated);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hearing added successfully!',
            ]);
        }
    }
    public function updateHearing(Hearing $hearing, Request $request)
    {
        $this->authorize('update', Hearing::class);
        $validated =   $request->validate([
            'title' => 'required|string',
            'court_branch_id'  => 'required||exists:court_branches,id',
            'hearing_date' => 'date|required',
            'status' => ['nullable', 'string', Rule::in(config('enums.hearing_status'))],
        ]);
        $hearing->update($validated);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "#$hearing->id Admin Fee updated successfully!",
            ]);
        }
    }
    public function deleteHearing(Hearing $hearing)
    {
        $this->authorize('delete', Hearing::class);
        $id = $hearing->id;
        $hearing->delete();

        return response()->json([
            'success' => true,
            'message' => "#$id Hearing deleted successfully!"
        ]);
    }
    public function addBilling(LawCase $lawCase, Request $request)
    {
        $this->authorize('create', Billing::class);
        $validated =  $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'billing_date'  => 'date|required',
            'due_date' => 'date|required|after_or_equal:billing_date',
        ]);
        $lawCase->billings()->create($validated);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Billing added successfully!',
            ]);
        }
    }
    public function updateBilling(Billing $billing, Request $request)
    {
        $this->authorize('update', Billing::class);
        $validated =  $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'billing_date'  => 'date|required',
            'due_date' => 'date|required|after_or_equal:billing_date',
        ]);
        $billing->update($validated);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "#$billing->id Admin Fee updated successfully!",
            ]);
        }
    }
    public function deleteBilling(Billing $billing)
    {
        $this->authorize('delete', Billing::class);
        if ($billing->deposits()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete Billing #{$billing->id} because it has existing payments."
            ], 400);
        }
        $id = $billing->id;
        $billing->delete();
        return response()->json([
            'success' => true,
            'message' => "#$id Billing deleted successfully!"
        ]);
    }

    public function updateStatus(LawCase $lawCase, Request $request)
    {

        $this->authorize('update', LawCase::class);
        $caseStatus = config('enums.case_status');
        $validated =  $request->validate([
            'status' => ['required', 'string', Rule::in($caseStatus)]
        ]);
        $lawCase->update($validated);


        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "#$lawCase->id Case changed to " . Str::headline($lawCase->status),
            ]);
        }
    }

    public function edit(LawCase $lawCase)
    {
        //
    }

    public function update(Request $request, LawCase $lawCase)
    {
        $this->authorize('update', LawCase::class);
        $caseTypes = config('enums.case_types');
        $partyRoles = config('enums.party_roles');
        $validated = $request->validate([
            'case_number' => 'required|unique:law_cases,case_number,' . $lawCase->id . '|max:255',
            'case_title' => 'required|max:255',
            'client_id' => 'required|exists:clients,id',
            'associate_id' => 'required|exists:associates,id',
            'opposing_party' => 'required|string|max:255',
            'case_type' => ['required', 'string', Rule::in($caseTypes)],
            'party_role' => ['required', 'string', Rule::in($partyRoles)],
        ]);
        $lawCase->update($validated);


        $changes = $lawCase->getChanges();

        // if (!empty($changes)) {
        //     $message = "The following fields were updated: ";
        //     foreach ($changes as $field => $newValue) {
        //         $message .= "$field, ";
        //     }
        //     $message = rtrim($message, ', ');
        // } else {
        //     $message = "No fields were changed.";
        // }
        $message = '';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "#$lawCase->id Case updated successfully!" . $message,
            ]);
        }
    }

    public function destroy(LawCase $lawCase, Request $request)
    {
        $this->authorize('delete', LawCase::class);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect.',
            ], 422);
        } else {
            $caseNumber = $lawCase->case_number;
            if ($lawCase->adminDeposits()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot {$caseNumber} because it has existing deposits."
                ], 400);
            }
            if ($lawCase->adminFees()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot {$caseNumber} because it has existing expenses."
                ], 400);
            }
            if ($lawCase->hearings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot {$caseNumber} because it has existing hearings."
                ], 400);
            }
            if ($lawCase->billings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot {$caseNumber} because it has existing bills."
                ], 400);
            }
            $lawCase->delete();
            return response()->json([
                'success' => true,
                'message' => $caseNumber . " has been deleted successfully. redirecting in 5 secs",
            ]);
        }
    }
    private function validateCase(Request $request)
    {
        $caseTypes = config('enums.case_types');
        $partyRoles = config('enums.party_roles');

        // Perform validation
        return $request->validate([
            'case_number' => 'required|unique:law_cases|max:255',
            'case_title' => 'required|max:255',
            'client_id' => 'required|exists:clients,id',
            // 'associate_id' => 'required|exists:associates,id',
            'opposing_party' => 'required|string|max:255',
            'case_type' => ['required', 'string', Rule::in($caseTypes)],
            'party_role' => ['required', 'string', Rule::in($partyRoles)],
        ]);
    }
    private function validateAdminDeposit(Request $request)
    {
        $validatedDeposits = [];

        if ($request->has('admin_deposit_payment_type_id')) {
            $payment_type_ids = $request->input('admin_deposit_payment_type_id');
            $amounts = $request->input('admin_deposit_amount');
            $dates = $request->input('admin_deposit_date');

            foreach ($payment_type_ids as $index => $payment_type_id) {
                $request->validate([
                    'admin_deposit_payment_type_id.' . $index => 'required|exists:payment_types,id',
                    'admin_deposit_amount.' . $index => 'required|numeric|min:0',
                    'admin_deposit_date.' . $index => 'required|date',
                ]);

                $validatedDeposits[] = [
                    'payment_type_id' => $payment_type_id,
                    'amount' => $amounts[$index],
                    'deposit_date' => $dates[$index],
                ];
            }
        }

        return $validatedDeposits;
    }
    private function validateAdminFee(Request $request)
    {
        $validatedAdminFees = [];
        if ($request->has('admin_fee_category_id')) {
            $adminFeeCategoryIds = $request->input('admin_fee_category_id');
            $amounts = $request->input('admin_fee_amount');
            $dates = $request->input('admin_fee_date');

            foreach ($adminFeeCategoryIds as $index => $categoryId) {
                $request->validate([
                    'admin_fee_category_id.' . $index => 'required|exists:administrative_fee_categories,id',
                    'admin_fee_amount.' . $index => 'required|numeric|min:0',
                    'admin_fee_date.' . $index => 'date|required',
                ]);
                $validatedAdminFees[] = [
                    'administrative_fee_category_id' => $categoryId,
                    'amount' => $amounts[$index],
                    'date' => $dates[$index],
                ];
            }
        }
        return $validatedAdminFees;
    }
    private function validateHearing(Request $request)
    {
        $validatedHearings = [];
        if ($request->has('hearing_court_branch_id')) {
            $titles = $request->input('hearing_title');
            $courtBranchIds = $request->input('hearing_court_branch_id');
            $hearingDates = $request->input('hearing_date');
            foreach ($courtBranchIds as $index => $courtBranchId) {
                $request->validate([
                    'hearing_title.' . $index => 'required|string',
                    'hearing_court_branch_id.' . $index => 'required||exists:court_branches,id',
                    'hearing_date.' . $index => 'date|required',
                ]);
                $validatedHearings[] = [
                    'title' => $titles[$index],
                    'court_branch_id' => $courtBranchId,
                    'hearing_date' => $hearingDates[$index],
                ];
            }
        }
        return $validatedHearings;
    }
    private function validateBilling(Request $request)
    {
        $validatedBillings = [];
        if ($request->has('billing_due_date')) {
            $titles = $request->input('billing_title');
            $amounts = $request->input('billing_amount');
            $billingDates = $request->input('billing_date');
            $dueDates = $request->input('billing_due_date');
            foreach ($dueDates as $index => $dueDate) {
                $request->validate([
                    'billing_title.' . $index => 'required|string',
                    'billing_amount.' . $index => 'required|numeric|min:0',
                    'billing_date.' . $index => 'date|required',
                    'billing_due_date.' . $index => 'date|required',
                ]);
                $validatedBillings[] = [
                    'title' => $titles[$index],
                    'amount' => $amounts[$index],
                    'billing_date' => $billingDates[$index],
                    'due_date' => $dueDate,
                ];
            }
        }
        return $validatedBillings;
    }
    protected function validateContacts(Request $request)
    {
        $validatedContacts = [];
        if ($request->has('contact_type')) {
            $contactTypes = $request->input('contact_type');
            $contactValues = $request->input('contact_value');
            $contactLabels = $request->input('contact_label');
            foreach ($contactTypes as $index => $contactType) {
                $request->validate([
                    'contact_type.' . $index => 'required|string|in:email,phone',
                    'contact_value.' . $index => 'required|string|max:255',
                    'contact_label.' . $index => 'nullable|string|max:255',
                ]);
                $validatedContacts[] = [
                    'contact_type' => $contactTypes[$index],
                    'contact_value' => $contactValues[$index],
                    'contact_label' => $contactLabels[$index]
                ];
            }
        }
        return $validatedContacts;
    }
}
