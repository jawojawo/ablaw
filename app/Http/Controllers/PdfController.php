<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class PdfController extends Controller
{
    public function billingInvoice(Billing $billing, Request $request)
    {

        $billing->load(['client']);
        if ($request->input('view')) {
            return view('pdf.invoice', ['billing' => $billing]);
        }
        if ($request->input('download')) {
            return Pdf::view('pdf.invoice', ['billing' => $billing])->download('AB-' . sprintf('%03d', $billing->id) . '.pdf');
        }
        return Pdf::view('pdf.invoice', ['billing' => $billing]);
    }
    public function acknowledgeReceipt(Deposit $deposit, Request $request)
    {
        if ($request->input('view')) {
            return view('pdf.acknowledge-receipt', ['deposit' => $deposit]);
        }
        if ($request->input('download')) {
            return Pdf::view('pdf.acknowledge-receipt', ['deposit' => $deposit])->landscape()->download('AR-' . sprintf('%03d', $deposit->id) . '.pdf');
        }
        return Pdf::view('pdf.acknowledge-receipt', ['deposit' => $deposit])->landscape();
        // return view('pdf.acknowledge-receipt', ['deposit' => $deposit]);
        // $deposit->load(['client']);
        // if ($request->input('download')) {
        //     return Pdf::view('pdf.invoice', ['billing' => $billing])->download('AB-' . sprintf('%03d', $billing->id) . '.pdf');
        // }
        // return Pdf::view('pdf.invoice', ['billing' => $billing])->save('AB-' . sprintf('%03d', $billing->id) . '.pdf');
    }
}
