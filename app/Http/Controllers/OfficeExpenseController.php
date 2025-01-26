<?php

namespace App\Http\Controllers;

use App\Models\OfficeExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelPdf\Facades\Pdf;

class OfficeExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->input('type');
        $description = $request->input('description');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        $types = OfficeExpense::select('type')->distinct()->limit(100)->pluck('type');
        $officeExpensesQuery = OfficeExpense::when($type, function ($query, $type) {
            $query->where('type', 'like', "%{$type}%");
        })
            ->when($description, function ($query, $description) {
                $query->where('description', 'like', "%{$description}%");
            })
            ->when($date_from, function ($query, $date_from) {
                $query->whereDate('expense_date', '>=', $date_from);
            })
            ->when($date_to, function ($query, $date_to) {
                $query->whereDate('expense_date', '<=', $date_to);
            })
            ->with(['user'])->withCount('notes');
        if ($date_from) {
            $minExpenseDate = $date_from;
        } else {
            $minExpenseDate = $officeExpensesQuery->min('expense_date');
        }
        if ($date_to) {
            $maxExpenseDate = $date_to;
        } else {
            $maxExpenseDate = $officeExpensesQuery->max('expense_date');
        }
        $totalAmount = $officeExpensesQuery->sum('amount');
        if ($request->pdf) {
            $officeExpenses = collect();
            $officeExpensesQuery->chunk(500, function ($chunk) use (&$officeExpenses) {
                $officeExpenses = $officeExpenses->concat($chunk);
            });
            switch ($request->pdf) {
                case 'view':
                    return Pdf::view(
                        'office-expense.pdf-view',
                        [
                            'officeExpenses' => $officeExpenses,
                            'types' => $types,
                            'minExpenseDate' => $minExpenseDate,
                            'maxExpenseDate' => $maxExpenseDate,
                            'totalAmount' => $totalAmount,
                        ]
                    )->margins(10, 10, 10, 10)->footerView('pdf.footer');
                    break;
                case 'download':
                    return Pdf::view(
                        'office-expense.pdf-view',
                        [
                            'officeExpenses' => $officeExpenses,
                            'types' => $types,
                            'minExpenseDate' => $minExpenseDate,
                            'maxExpenseDate' => $maxExpenseDate,
                            'totalAmount' => $totalAmount,
                        ]
                    )->margins(10, 10, 10, 10)->footerView('pdf.footer')->download('Deposit Recieved.pdf');
                    break;
            }
        }
        $officeExpenses = $officeExpensesQuery->orderBy('expense_date', 'desc')->paginate(15);
        return view('office-expense.index', [
            'officeExpenses' => $officeExpenses,
            'types' => $types,
            'minExpenseDate' => $minExpenseDate,
            'maxExpenseDate' => $maxExpenseDate,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = OfficeExpense::select('type')->distinct()->limit(100)->pluck('type');


        return view('office-expense.create', ['types' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'date|required',
            'description' => 'nullable|string|max:500',
        ]);
        $officeExpense = OfficeExpense::create($validatedData);
        return redirect()->route('officeExpense.show', $officeExpense->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, OfficeExpense $officeExpense)
    {
        $types = OfficeExpense::select('type')->distinct()->limit(100)->pluck('type');
        if ($request->ajax()) {
            switch ($request->table) {
                case  "notes":
                    return view('components.note.notes-table', ['notes' => $officeExpense->notes()])->render();
                    break;

                default:
                    return '';
                    break;
            }
        }
        $officeExpense->loadCount(['notes']);
        return view('office-expense.show', ['officeExpense' => $officeExpense, 'types' => $types]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfficeExpense $officeExpense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfficeExpense $officeExpense)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'date|required',
            'description' => 'nullable|string|max:500',
        ]);
        $officeExpense->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => "#$officeExpense->id Office Expense updated successfully!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficeExpense $officeExpense, Request $request)
    {
        $this->authorize('delete', OfficeExpense::class);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect.',
            ], 422);
        } else {
            $officeExpense->delete();
            return response()->json([
                'success' => true,
                'message' => "Office Expense has been deleted successfully. redirecting in 5 secs",
            ]);
        }
    }
}
