<?php

namespace App\Http\Controllers;

use App\Models\CourtBranch;
use App\Models\Hearing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CourtBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courtTypes = CourtBranch::select('type')->limit(100)->distinct()->get();
        $regions = CourtBranch::select('region')->limit(100)->distinct()->get();

        $region = $request->input('region');
        $city = $request->input('city');
        $type = $request->input('type');
        $branch = $request->input('branch');

        $courtBranches = CourtBranch::when($region, function ($query, $region) {
            $query->where('region',  "{$region}");
        })->when($city, function ($query, $city) {
            $query->where('city', 'like', "%{$city}%");
        })->when($type, function ($query, $type) {
            $query->where('type',  "{$type}");
        })->when($branch, function ($query, $branch) {
            $query->where('branch', 'like', "%{$branch}%");
        })->withCount(['notes', 'contacts', 'hearings'])->orderBy('hearings_count', 'desc')->paginate(15);

        //$courtBranches = CourtBranch::withCount(['notes', 'contacts', 'hearings'])->paginate(15);

        return view('court-branch.index', ['courtBranches' => $courtBranches, 'courtTypes' => $courtTypes, 'regions' => $regions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $regions = CourtBranch::distinct('region')->limit(100)->pluck('region');
        $courtTypes = CourtBranch::distinct('type')->limit(100)->pluck('type');

        return view('court-branch.create', ['courtTypes' => $courtTypes, 'regions' => $regions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $contacts = [];
        if ($request->has('contact_type')) {
            foreach ($request->input('contact_type') as $index => $type) {
                $contacts[] = [
                    'contact_type' => $type,
                    'contact_value' => $request->input('contact_value')[$index] ?? null,
                    'contact_label' => $request->input('contact_label')[$index] ?? null,
                ];
            }
        }

        // Validate the data
        $validatedData = $request->validate([
            'region' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'judge' => 'nullable|string|max:255',

            'contacts' => 'nullable|array',
            'contacts.*.contact_type' => 'required|string|in:email,phone',
            'contacts.*.contact_value' => 'required|string|max:255',
            'contacts.*.contact_label' => 'nullable|string|max:255',
        ]);

        // Create the client
        $courtBranch = CourtBranch::create([
            'region' => $validatedData['region'],
            'city' => $validatedData['city'],
            'type' => $validatedData['type'],
            'branch' => $validatedData['branch'] ?? null,
            'judge' => $validatedData['judge'] ?? null,
            'address' => $validatedData['address'] ?? null,
        ]);

        // Store the contacts
        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                $courtBranch->contacts()->create($contact);
            }
        }
        return redirect()->route('courtBranch.show', $courtBranch->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(CourtBranch $courtBranch, Request $request)
    {
        $courtBranch->loadCount(['notes']);
        $regions = CourtBranch::distinct('region')->limit(100)->pluck('region');
        $courtTypes = CourtBranch::distinct('type')->limit(100)->pluck('type');

        if ($request->ajax()) {
            switch ($request->table) {
                case  "hearings":
                    return view('components.hearing.hearings-table', ['hearings' => $courtBranch->hearings(), 'multiCase' => true, 'noEdit' => true, 'addHearings' => false])->render();
                    break;
                case  "notes":
                    return view('components.note.notes-table', ['notes' => $courtBranch->notes()])->render();
                    break;

                default:
                    return '';
                    break;
            }
        }
        return view('court-branch.show', [
            'courtBranch' => $courtBranch,
            'regions' => $regions,
            'courtTypes' => $courtTypes,
        ]);
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
        $validatedData = $request->validate([
            'region' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'judge' => 'nullable|string|max:255',
        ]);
        $courtBranch->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => "#$courtBranch->id Court Branch updated successfully!"
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourtBranch $courtBranch, Request $request)
    {

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect.',
            ], 422);
        } else {
            if ($courtBranch->hearings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete court branch because it has existing hearings."
                ], 400);
            }
            $courtBranch->delete();
            return response()->json([
                'success' => true,
                'message' => "Court Branch has been deleted successfully. redirecting in 5 secs",
            ]);
        }
    }
}
