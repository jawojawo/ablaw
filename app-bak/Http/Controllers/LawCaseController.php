<?php

namespace App\Http\Controllers;

use App\Models\AdminDeposit;
use App\Models\AdministrativeFee;
use App\Models\AdministrativeFeeCategory;
use App\Models\Billing;
use App\Models\Hearing;
use App\Models\LawCase;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class LawCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->input('search');
        $caseTypes = $request->input('case_type', []);
        $partyRoles = $request->input('party_role', []);
        $opposingParty = $request->input('opposing_party');
        $status = $request->input('status', []);
        $cases = LawCase::query()->with(['client', 'associate', 'hearings', 'billings', 'user'])
            ->when($search, function ($query, $search) {
                $query->where('case_number', 'like', "%{$search}%")->orWhere('case_title', 'like', "%{$search}%");
            })
            ->when($caseTypes, function ($query, $caseTypes) {
                $query->whereIn('case_type', $caseTypes);
            })
            ->when($partyRoles, function ($query, $partyRoles) {
                $query->whereIn('party_role', $partyRoles);
            })
            ->when($opposingParty, function ($query, $opposingParty) {
                $query->where('opposing_party', 'like', "%{$opposingParty}%");
            })
            ->when($status, function ($query, $status) {
                $query->whereIn('status', $status);
            })
            ->orderBy('updated_at', 'desc')
            ->with(['client', 'associate'])
            ->paginate(15);

        return view('case.index', ['cases' => $cases]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminFeeCategories = AdministrativeFeeCategory::all();
        $paymentTypes = PaymentType::all();
        return view('case.create', ['adminFeeCategories' => $adminFeeCategories, 'paymentTypes' => $paymentTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $this->validateCase($request);
        $validatedAdminDeposits = $this->validateAdminDeposit($request);
        $validatedAdminFees = $this->validateAdminFee($request);
        $validatedHearings = $this->validateHearing($request);
        $validatedBillings = $this->validateBilling($request);
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
            DB::commit();

            return redirect()->route('case.show', $lawCase->id)->with('success', 'Case created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            //Log::error('Error creating case: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error creating case.');
        }
    }

    public function show(LawCase $lawCase, Request $request)
    {
        if ($request->ajax()) {
            switch ($request->table) {
                case  "admin-deposits":
                    return view('components.admin-deposit.admin-deposits-table', ['adminDeposits' => $lawCase->adminDeposits()])->render();
                    break;
                case  "admin-fees":
                    return view('components.admin-fee.admin-fees-table', ['adminFees' => $lawCase->adminFees()])->render();
                    break;
                case  "hearings":
                    return view('components.hearing.hearings-table', ['hearings' => $lawCase->hearings()])->render();
                    break;
                case  "billings":
                    return view('components.billing.billings-table', ['billings' => $lawCase->billings()->orderedBillings()])->render();
                    break;
                case  "extra-info":
                    return view('components.extra-info', ['lawCase' => $lawCase])->render();
                    break;
                case  "billing-deposits":
                    $billing = Billing::find($request->id);
                    return view('components.billing-deposit.billing-deposits-table', ['billing' => $billing])->render();
                    break;

                default:
                    return '';
                    break;
            }
        }
        $adminFeeCategories = AdministrativeFeeCategory::all();
        $paymentTypes = PaymentType::all();
        //$lawCase->load(['client',  'associate', 'adminDeposits', 'adminFees', 'hearings', 'billings',]);


        return view('case.show', [
            'lawCase' => $lawCase,
            'adminFeeCategories' => $adminFeeCategories,
            'paymentTypes' => $paymentTypes,

        ]);
    }
    public function addAdminDeposit(LawCase $lawCase, Request $request)
    {
        $validated = $request->validate([
            'payment_type_id' =>  'required|exists:payment_types,id',
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

        $validated = $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
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
        $id = $adminDeposit->id;
        $adminDeposit->delete();

        return response()->json([
            'success' => true,
            'message' => "#$id Admin deposit deleted successfully!"
        ]);
    }

    public function addAdminFee(LawCase $lawCase, Request $request)
    {
        $validated = $request->validate([

            'administrative_fee_category_id' => 'required|exists:administrative_fee_categories,id',
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
        $validated = $request->validate([
            'administrative_fee_category_id' => 'required|exists:administrative_fee_categories,id',
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
        $id = $adminFee->id;
        $adminFee->delete();

        return response()->json([
            'success' => true,
            'message' => "#$id Admin Fee deleted successfully!"
        ]);
    }

    public function addHearing(LawCase $lawCase, Request $request)
    {
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
        $validated =   $request->validate([
            'title' => 'required|string',
            'court_branch_id'  => 'required||exists:court_branches,id',
            'hearing_date' => 'date|required',
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
        $id = $hearing->id;
        $hearing->delete();

        return response()->json([
            'success' => true,
            'message' => "#$id Hearing deleted successfully!"
        ]);
    }
    public function addBilling(LawCase $lawCase, Request $request)
    {
        $validated =  $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'billing_date'  => 'date|required',
            'due_date' => 'date|required',
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
        $validated =  $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'billing_date'  => 'date|required',
            'due_date' => 'date|required',
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
        if ($billing->deposits()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete Billing #{$billing->id} because it has existing deposits."
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

    public function destroy(LawCase $lawCase)
    {
        //
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
            'associate_id' => 'required|exists:associates,id',
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
}
