<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeFeeCategory;
use Illuminate\Http\Request;

class AdminFeeCategoryController extends Controller
{
    public function index()
    {
        $adminFeeCatetgories = AdministrativeFeeCategory::paginate(15);
        return view('settings.admin-fee-category.index', ['adminFeeCategories' => $adminFeeCatetgories]);
    }
    public function create()
    {
        return view('settings.admin-fee-category.create');
    }
    public function store(Request $request)
    {

        $validatedData =   $request->validate([
            'name' => 'required|unique:administrative_fee_categories',
            'amount' => 'required|numeric',
        ]);
        AdministrativeFeeCategory::create($validatedData);
        return redirect()->route('settings.adminFeeCategory')->with('success', 'Administrative Fee Category created successfully!');
    }
}
