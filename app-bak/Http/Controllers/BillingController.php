<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Deposit;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('billing.index');
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

        $validated = $request->validate([
            'payment_type_id' =>  'required|exists:payment_types,id',
            'amount' => 'required|numeric|min:1',
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
        $validated = $request->validate([
            'payment_type_id' =>  'required|exists:payment_types,id',
            'amount' => 'required|numeric|min:1',
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
}
