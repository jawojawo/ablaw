<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $depositsFrom = $request->input('deposits_from');
        $depositsTo = $request->input('deposits_to');
        $depositsQuery = Deposit::withCount(['notes'])->with(['lawCase', 'billing', 'lawCase.client'])->orderBy('deposit_date', 'desc')
            ->when($depositsFrom, function ($query, $depositsFrom) {
                $query->whereDate('deposit_date', '>=', $depositsFrom);
            })
            ->when($depositsTo, function ($query, $depositsTo) {
                $query->whereDate('deposit_date', '<=', $depositsTo);
            });
        if ($depositsFrom) {
            $minDepositDate = $depositsFrom;
        } else {
            $minDepositDate = $depositsQuery->min('deposit_date');
        }
        if ($depositsTo) {
            $maxDepositDate = $depositsTo;
        } else {
            $maxDepositDate = $depositsQuery->max('deposit_date');
        }

        $totalAmount = $depositsQuery->sum('amount');

        if ($request->pdf) {
            $deposits = collect();
            $depositsQuery->chunk(500, function ($chunk) use (&$deposits) {
                $deposits = $deposits->concat($chunk);
            });
            switch ($request->pdf) {
                case 'view':
                    return Pdf::view('billing-deposit.pdf-view',  [
                        'deposits' => $deposits,
                        'minDepositDate' => $minDepositDate,
                        'maxDepositDate' => $maxDepositDate,
                        'totalAmount' => $totalAmount,
                        'pdf' => true,
                    ])->margins(10, 10, 10, 10)->footerView('pdf.footer');
                    break;
                case 'download':
                    return Pdf::view('billing-deposit.pdf-view',  [
                        'deposits' => $deposits,
                        'minDepositDate' => $minDepositDate,
                        'maxDepositDate' => $maxDepositDate,
                        'totalAmount' => $totalAmount,
                        'pdf' => true,
                    ])->margins(10, 10, 10, 10)->footerView('pdf.footer')->download('Deposit Recieved.pdf');
                    break;
            }
        }


        $deposits = $depositsQuery->paginate(15);
        return view('billing-deposit.index', [
            'deposits' => $deposits,
            'minDepositDate' => $minDepositDate,
            'maxDepositDate' => $maxDepositDate,
            'totalAmount' => $totalAmount,
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

    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deposit $deposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deposit $deposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposit $deposit)
    {
        //
    }
}
