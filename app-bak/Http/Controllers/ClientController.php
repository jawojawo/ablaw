<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeFeeCategory;
use App\Models\Billing;
use App\Models\Client;
use App\Models\Hearing;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $clients = Client::
            //with(['billings', 'hearings', 'lawCases', 'adminDeposits'])->
            paginate(15);
        return view('client.index', ['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
                    return view('components.admin-deposit.admin-deposits-table', ['adminDeposits' => $client->adminDeposits()->whereNotIn('law_case_id', $excludedCaseIds)])->render();
                    break;
                case  "admin-fees":
                    return view('components.admin-fee.admin-fees-table', ['adminFees' => $client->adminFees()->whereNotIn('law_case_id', $excludedCaseIds)])->render();
                    break;
                case  "hearings":
                    return view('components.hearing.hearings-table', ['hearings' => $client->hearings()->whereNotIn('law_case_id', $excludedCaseIds)])->render();
                    break;
                case  "billings":
                    return view('components.billing.billings-table', ['billings' => $client->billings()->orderedBillings()->whereNotIn('law_case_id', $excludedCaseIds)])->render();
                    break;
                case  "extra-info":
                    $client->load([
                        'lawCases' => function ($query) use ($excludedCaseIds) {
                            if (!empty($excludedCaseIds)) {
                                $query->whereNotIn('id', $excludedCaseIds);
                            }
                        }
                    ]);
                    $client->loadCount(['adminDeposits', 'hearings', 'adminFees', 'billings']);
                    $client->loadCount([
                        'hearings as upcoming_hearing_count' => function ($query) {
                            $query->where('hearing_date', '>', Carbon::now());
                        },
                    ]);
                    $client->loadCount([
                        'hearings as past_hearing_count' => function ($query) {
                            $query->where('hearing_date', '<', Carbon::now());
                        },
                    ]);
                    return view('components.client-extra-info', ['client' => $client])->render();
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
        return view('client.show', [
            'client' => $client,
            'adminFeeCategories' => $adminFeeCategories,
            'paymentTypes' => $paymentTypes,
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
