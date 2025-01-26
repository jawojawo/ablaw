<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use Illuminate\Http\Request;

class AssociateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $associates = Associate::paginate(15);
        return view('associate.index', ['associates' => $associates]);
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
    public function show(Associate $associate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Associate $associate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Associate $associate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Associate $associate)
    {
        //
    }
}
