<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeFeeCategory;
use App\Models\Billing;
use App\Models\Client;
use App\Models\Hearing;
use App\Models\Note;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view', Client::class);
        $name = $request->input('name');

        $clients = Client::when($name, function ($query, $name) {
            $keywords = explode(' ', $name); // Split the name into individual words
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('name', 'like', "%{$keyword}%");
                }
            });
        })->withCount(['lawCases', 'billings', 'contacts', 'notes', 'overDueBills', 'upcomingBills', 'dueTodayBills', 'paidBills'])
            ->withSum('adminDeposits', 'amount')->withSum('adminFees', 'amount')->paginate(15);
        $clients->getCollection()->transform(function ($client) {
            $client->total_remaining_deposit = $client->admin_deposits_sum_amount - $client->admin_fees_sum_amount;
            return $client;
        });
        return view('client.index', ['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('client.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Rebuild contacts array
        $contacts = [];
        if ($request->has('contact_type')) {
            foreach ($request->input('contact_type') as $index => $type) {
                $contacts[] = [
                    'contact_type' => $type,
                    'contact_value' => $request->input('contact_value')[$index] ?? null,
                    'contact_label' => $request->input('contact_label')[$index] ?? null,
                ];
            }
        }

        // Validate the data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'contacts' => 'nullable|array',
            'contacts.*.contact_type' => 'required|string|in:email,phone',
            'contacts.*.contact_value' => 'required|string|max:255',
            'contacts.*.contact_label' => 'nullable|string|max:255',
        ]);

        // Create the client
        $client = Client::create([
            'name' => $validatedData['name'],
            'suffix' => $validatedData['suffix'] ?? null,
            'address' => $validatedData['address'] ?? null,
        ]);

        // Store the contacts
        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                $client->contacts()->create($contact);
            }
        }

        return redirect()->route('client.show', $client->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client, Request $request)
    {
        if ($request->ajax()) {
            $excludedCaseIds = $request->get('excluded_case_ids', []);
            switch ($request->table) {
                case  "admin-deposits":
                    return view('components.admin-deposit.admin-deposits-table', ['adminDeposits' => $client->adminDeposits()->orderBy('law_case_id')->whereNotIn('law_case_id', $excludedCaseIds), 'multiCase' => true])->render();
                    break;
                case  "admin-fees":
                    return view('components.admin-fee.admin-fees-table', ['adminFees' => $client->adminFees()->orderBy('law_case_id')->whereNotIn('law_case_id', $excludedCaseIds), 'multiCase' => true])->render();
                    break;
                case  "hearings":
                    return view('components.hearing.hearings-table', ['hearings' => $client->hearings()->orderBy('law_case_id')->whereNotIn('law_case_id', $excludedCaseIds), 'multiCase' => true])->render();
                    break;
                case  "billings":
                    return view('components.billing.billings-table', ['billings' => $client->billings()->orderBy('law_case_id')->whereNotIn('law_case_id', $excludedCaseIds), 'multiCase' => true])->render();
                    break;
                case  "extra-info":
                    // $client->setRelation(
                    //     'lawCases',
                    //     $client->lawCases()->when(!empty($excludedCaseIds), function ($query) use ($excludedCaseIds) {
                    //         $query->whereNotIn('id', $excludedCaseIds);
                    //     })->get()
                    // );
                    // $client->loadCount(['adminDeposits', 'hearings', 'adminFees', 'billings']);
                    $client->loadCount([
                        'adminDeposits' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds);
                        },
                        'hearings' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds);
                        },
                        'adminFees' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds);
                        },
                        'billings' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds);
                        },
                    ]);
                    // $lawCase->loadCount(['upcomingHearings', 'ongoingHearings', 'completedHearings', 'canceledHearings']);
                    $client->loadCount([
                        'hearings as upcoming_hearings_count' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds)->where('hearings.status', 'upcoming')->where('hearing_date', '>', Carbon::now());
                        },
                    ]);
                    $client->loadCount([
                        'hearings as ongoing_hearings_count' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds)->where('hearings.status', 'upcoming')->where('hearing_date', '<=', Carbon::now());
                        },
                    ]);
                    $client->loadCount([
                        'hearings as completed_hearings_count' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds)->where('hearings.status', 'completed');
                        },
                    ]);
                    $client->loadCount([
                        'hearings as canceled_hearings_count' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds)->where('hearings.status', 'canceled');
                        },
                    ]);
                    $nextHearing = $client->hearings()
                        ->whereNotIn('law_case_id', $excludedCaseIds)
                        ->where('hearing_date', '>', now())
                        ->orderBy('hearing_date', 'asc')
                        ->first();
                    $client->setRelation('next_hearing', $nextHearing);
                    $client->load([
                        'billings' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds);
                        },
                    ]);
                    $client->loadCount([
                        'overDueBills' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds);
                        },
                        'upcomingBills' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds);
                        },
                        'dueTodayBills' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds);
                        },
                        'paidBills' => function ($query) use ($excludedCaseIds) {
                            $query->whereNotIn('law_case_id', $excludedCaseIds);
                        },
                    ]);

                    return view('components.client-extra-info', ['client' => $client])->render();
                    break;
                case  "billing-deposits":
                    $billing = Billing::find($request->id);
                    return view('components.billing-deposit.billing-deposits-table', ['billing' => $billing])->render();
                    break;
                case  "notes":
                    return view('components.note.notes-table', ['notes' => $client->notes()])->render();
                    break;

                default:
                    return '';
                    break;
            }
        }
        //        $adminFeeCategories = AdministrativeFeeCategory::all();
        // $paymentTypes = PaymentType::all();
        $client->loadCount(['lawCases', 'notes']);
        return view('client.show', [
            'client' => $client,
            //  'adminFeeCategories' => $adminFeeCategories,
            //'paymentTypes' => $paymentTypes,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
        ]);
        $client->update($validated);
        return response()->json([
            'success' => true,
            'message' => "#$client->id Client updated successfully!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client, Request $request)
    {
        $this->authorize('delete', Client::class);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect.',
            ], 422);
        } else {
            if ($client->lawCases()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete client because it has existing cases."
                ], 400);
            }
            $client->delete();
            return response()->json([
                'success' => true,
                'message' => "Client has been deleted successfully. redirecting in 5 secs",
            ]);
        }
    }
}
