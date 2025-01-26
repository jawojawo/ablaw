<?php

namespace App\Http\Controllers;

use App\Models\CourtBranch;
use Illuminate\Http\Request;

class CourtBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courtBranches = CourtBranch::paginate(15);
        return view('court-branch.index', ['courtBranches' => $courtBranches]);
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
    public function show(CourtBranch $courtBranch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourtBranch $courtBranch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourtBranch $courtBranch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourtBranch $courtBranch)
    {
        //
    }
}
