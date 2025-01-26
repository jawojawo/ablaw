<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Hearing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class CalendarController extends Controller
{
    public function index()
    {

        return view('home');
    }
    public function dateInfo($date)
    {
        try {
            $parsedDate = Carbon::parse($date);
            $hearings = Hearing::whereDate('hearing_date', $parsedDate)->get();
            $billings = Billing::whereDate('due_date', $parsedDate)->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'hearings' => ['total' => $hearings->count(), 'data' => $hearings],
                    'billings' => ['total' => $billings->count(), 'data' => $billings],
                ],
            ]);
        } catch (\InvalidArgumentException $e) { // More specific exception for invalid date
            return response()->json([
                'success' => false,
                'error' => 'Invalid date format provided. Use the format YYYY-MM-DD.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An unexpected error occurred.',
            ], 500);
        }
    }
    public function monthInfo($date)
    {
        try {
            $parsedDate = Carbon::parse($date);
            $startOfMonth = Carbon::parse($date)->startOfMonth();
            $endOfMonth = Carbon::parse($date)->endOfMonth();
            // dd(Carbon::parse($date)->startOfMonth());
            // //dd($parsedDate);
            // dd($startOfMonth);
            // dd($endOfMonth);
            // $query = Hearing::whereBetween('hearing_date', [$startOfMonth, $endOfMonth])->ddRawSql();

            $hearingsCount = Hearing::whereBetween('hearing_date', [$startOfMonth, $endOfMonth])->count();
            $billingsCount = Billing::whereBetween('due_date', [$startOfMonth, $endOfMonth])->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'hearings' => ['total' => $hearingsCount],
                    'billings' => ['total' => $billingsCount],
                ],
            ]);
        } catch (\InvalidArgumentException $e) { // For invalid date format
            return response()->json([
                'success' => false,
                'error' => 'Invalid date format provided. Use the format YYYY-MM-DD.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An unexpected error occurred.',
            ], 500);
        }
    }
}
