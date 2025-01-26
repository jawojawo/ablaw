<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Deposit;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelPdf\Facades\Pdf;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //$paymentTypes = PaymentType::all();
        if ($request->ajax() && $request->table == 'billing-deposits') {
            $billing = Billing::find($request->id);
            return view('components.billing-deposit.billing-deposits-table', ['billing' => $billing])->render();
        }
        $title = $request->input('title');
        $billingDateFrom = $request->input('billing_date_from');
        $billingDateTo = $request->input('billing_date_to');
        $dueDateFrom = $request->input('due_date_from');
        $dueDateTo = $request->input('due_date_to');
        // $depositFrom = $request->input('deposit_from');
        // $depositTo = $request->input('deposit_to');
        // $billingDateRange = $request->input('billing_date');
        // $dueDateRange = $request->input('due_date');
        $bills = $request->input('bills', []);
        // $billingStart = $billingEnd = null;
        // $dueStart = $dueEnd = null;

        // if ($billingDateRange) {
        //     $dates = explode(' to ', $billingDateRange);
        //     $billingStart = Carbon::parse($dates[0]);
        //     $billingEnd = isset($dates[1]) ? Carbon::parse($dates[1]) : $billingStart;
        // }


        // if ($dueDateRange) {
        //     $dates = explode(' to ', $dueDateRange);
        //     $dueStart = Carbon::parse($dates[0]);

        //     $dueEnd = isset($dates[1]) ? Carbon::parse($dates[1]) : $dueStart;
        // }
        $billsQuery = Billing::when($title, function ($query, $title) {
            $query->where('title', 'like', "%{$title}%");
        })
            ->when($billingDateFrom, function ($query, $billingDateFrom) {
                $query->whereDate('billing_date', '>=', $billingDateFrom);
            })
            ->when($billingDateTo, function ($query, $billingDateTo) {
                $query->whereDate('billing_date', '<=', $billingDateTo);
            })
            ->when($dueDateFrom, function ($query, $dueDateFrom) {
                $query->whereDate('due_date', '>=', $dueDateFrom);
            })
            ->when($dueDateTo, function ($query, $dueDateTo) {
                $query->whereDate('due_date', '<=', $dueDateTo);
            })

            ->when($bills, function ($query) use ($bills) {
                $query->where(function ($query) use ($bills) {
                    if (in_array('overDue', $bills)) {
                        $query->where(function ($subQuery) {
                            $subQuery->where('status', 'paid')
                                ->orWhereDate('due_date', '>=', Carbon::today());
                        });
                    }
                    if (in_array('upcoming', $bills)) {
                        // Exclude upcoming bills
                        $query->where(function ($subQuery) {
                            $subQuery->where('status', 'paid')
                                ->orWhereDate('due_date', '<=', Carbon::today());
                        });
                    }
                    if (in_array('dueToday', $bills)) {
                        // Exclude due today bills
                        $query->where(function ($subQuery) {
                            $subQuery->where('status', 'paid')
                                ->orWhereDate('due_date', '!=', Carbon::today());
                        });
                    }
                    if (in_array('paid', $bills)) {
                        // Exclude paid bills
                        $query->whereNot('status', 'paid');
                    }
                });
            })

            // ->when($bills, function ($query) use ($bills) {
            //     $query->where(function ($query) use ($bills) {
            //         if (in_array('overDue', $bills)) {
            //             $query->whereNotIn('status', ['paid'])->whereDate('due_date', '<', Carbon::today());
            //         }
            //         if (in_array('upcoming', $bills)) {
            //             $query->whereNotIn('status', ['paid'])->whereDate('due_date', '>', Carbon::today());
            //         }
            //         if (in_array('dueToday', $bills)) {
            //             $query->whereNotIn('status', ['paid'])->whereDate('due_date', Carbon::today());
            //         }
            //         if (in_array('paid', $bills)) {
            //             $query->where('status', 'paid');
            //         }
            //     });
            // })
            ->with(['user', 'lawCase', 'deposits'])
            ->withCount(['notes', 'deposits']);

        $totalAmount = $billsQuery->sum('amount');
        $totalPaid = $billsQuery->sum('total_paid');

        $outstandingQuery = (clone $billsQuery)->whereNotIn('status', ['paid']);
        $a = $outstandingQuery->sum('amount');
        $b = $outstandingQuery->sum('total_paid');
        $totalOutstandingBalance = $a - $b;

        $overDueCount =  (clone $billsQuery)->whereNotIn('status', ['paid'])->whereDate('due_date', '<', Carbon::today())->count();
        $dueTodayCount =  (clone $billsQuery)->whereNotIn('status', ['paid'])->whereDate('due_date', Carbon::today())->count();
        $upcomingCount =  (clone $billsQuery)->whereNotIn('status', ['paid'])->whereDate('due_date', '>', Carbon::today())->count();
        $paidCount =  (clone $billsQuery)->where('status', 'paid')->count();

        if ($billingDateFrom) {
            $minBillDate = $billingDateFrom;
        } else {
            $minBillDate = $billsQuery->min('billing_date');
        }
        if ($billingDateTo) {
            $maxBillDate = $billingDateTo;
        } else {
            $maxBillDate = $billsQuery->max('billing_date');
        }



        //  $billingIds = $billsQuery->pluck('id');

        //  $allDeposits = collect(); // Create an empty collection

        // foreach ($billingIds->chunk(500) as $chunk) {
        //     $deposits = Deposit::whereIn('billing_id', $chunk)->when($depositFrom, function ($query, $depositFrom) {
        //         $query->whereDate('deposit_date', '>=', $depositFrom);
        //     })
        //         ->when($depositTo, function ($query, $depositTo) {
        //             $query->whereDate('deposit_date', '<=', $depositTo);
        //         })->get();
        //     $allDeposits = $allDeposits->merge($deposits); // Merge deposits into the collection
        // }

        // Now you can sum the deposits
        // $totalDeposits = $allDeposits->sum('amount');
        // $minDepositDate = $allDeposits->min('deposit_date');
        // $maxDepositDate = $allDeposits->max('deposit_date');
        if ($request->pdf) {
            $bills = collect();
            $billsQuery->chunk(500, function ($chunk) use (&$bills) {
                $bills = $bills->concat($chunk);
            });
            switch ($request->pdf) {
                case 'view':
                    return Pdf::view(
                        'billing.pdf-view',
                        [
                            'bills' => $bills,
                            // 'totalDeposits' => $totalDeposits,
                            // 'minDepositDate' => $minDepositDate,
                            // 'maxDepositDate' => $maxDepositDate,
                            'totalAmount' => $totalAmount,
                            'totalPaid' => $totalPaid,
                            'totalOutstandingBalance' => $totalOutstandingBalance,
                            'overDueCount' => $overDueCount,
                            'dueTodayCount' => $dueTodayCount,
                            'upcomingCount' => $upcomingCount,
                            'paidCount' => $paidCount,
                            'minBillDate' => $minBillDate,
                            'maxBillDate' => $maxBillDate,
                        ]
                    )->margins(10, 10, 10, 10)->footerView('pdf.footer');
                    break;
                case 'download':
                    return Pdf::view(
                        'billing.pdf-view',
                        [
                            'bills' => $bills,
                            // 'totalDeposits' => $totalDeposits,
                            // 'minDepositDate' => $minDepositDate,
                            // 'maxDepositDate' => $maxDepositDate,
                            'totalAmount' => $totalAmount,
                            'totalPaid' => $totalPaid,
                            'totalOutstandingBalance' => $totalOutstandingBalance,
                            'overDueCount' => $overDueCount,
                            'dueTodayCount' => $dueTodayCount,
                            'upcomingCount' => $upcomingCount,
                            'paidCount' => $paidCount,
                            'minBillDate' => $minBillDate,
                            'maxBillDate' => $maxBillDate,
                        ]
                    )->margins(10, 10, 10, 10)->footerView('pdf.footer')->download('Deposit Recieved.pdf');
                    break;
            }
        }
        $bills = $billsQuery->orderedBillings()->paginate(15);
        if ($request->ajax()) {
            return view(
                'billing.partials.billings-table',
                [
                    'bills' => $bills,
                    // 'totalDeposits' => $totalDeposits,
                    // 'minDepositDate' => $minDepositDate,
                    // 'maxDepositDate' => $maxDepositDate,
                    'totalAmount' => $totalAmount,
                    'totalPaid' => $totalPaid,
                    'totalOutstandingBalance' => $totalOutstandingBalance,
                    'overDueCount' => $overDueCount,
                    'dueTodayCount' => $dueTodayCount,
                    'upcomingCount' => $upcomingCount,
                    'paidCount' => $paidCount,
                    'minBillDate' => $minBillDate,
                    'maxBillDate' => $maxBillDate,
                ]
            )->render();
        }
        return view('billing.index', [
            'bills' => $bills,
            // 'totalDeposits' => $totalDeposits,
            // 'minDepositDate' => $minDepositDate,
            // 'maxDepositDate' => $maxDepositDate,
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid,
            'totalOutstandingBalance' => $totalOutstandingBalance,
            'overDueCount' => $overDueCount,
            'dueTodayCount' => $dueTodayCount,
            'upcomingCount' => $upcomingCount,
            'paidCount' => $paidCount,
            'minBillDate' => $minBillDate,
            'maxBillDate' => $maxBillDate,
        ]);
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
    public function addDeposit(Billing $billing, Request $request)
    {
        $paymentTypes = config('enums.payment_types');
        $validated = $request->validate([
            'payment_type' => ['required', 'string', Rule::in($paymentTypes)],
            'amount' => 'required|numeric|min:1',
            'received_from' => 'nullable|string|max:100',
            'deposit_date' => 'required|date',
        ]);
        $billing->deposits()->create($validated);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Deposit added successfully!',
            ]);
        }
    }
    public function updateDeposit(Deposit $deposit, Request $request)
    {
        $paymentTypes = config('enums.payment_types');
        $validated = $request->validate([
            'payment_type' => ['required', 'string', Rule::in($paymentTypes)],
            'amount' => 'required|numeric|min:1',
            'received_from' => 'nullable|string|max:100',
            'deposit_date' => 'required|date',
        ]);
        $deposit->update($validated);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Deposit updated successfully!',
            ]);
        }
    }
    public function deleteDeposit(Deposit $deposit, Request $request)
    {
        $id = $deposit->id;
        $deposit->delete();

        return response()->json([
            'success' => true,
            'message' => "#$id Bill Deposit deleted successfully!"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Billing $billing)
    {

        return view('billing.show', ['billing' => $billing]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Billing $billing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Billing $billing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Billing $billing)
    {
        //
    }
    // public function overDueBills()
    // {
    //     return Billing::whereNotIn('status', ['paid'])->whereDate('due_date', '<', Carbon::today());
    // }
    // public function dueTodayBills()
    // {
    //     return Billing::whereNotIn('status', ['paid'])->whereDate('due_date', Carbon::today());
    // }
    // public function upcomingBills()
    // {
    //     return Billing::whereNotIn('status', ['paid'])->whereDate('due_date', '>', Carbon::today());
    // }
    // public function paidBills()
    // {
    //     return Billing::where('status', 'paid');
    // }
}
