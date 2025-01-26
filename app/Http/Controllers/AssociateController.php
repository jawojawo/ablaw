<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AssociateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->input('name');
        $associates = Associate::when($name, function ($query, $name) {
            $keywords = explode(' ', $name); // Split the name into individual words
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('name', 'like', "%{$keyword}%");
                }
            });
        })->withCount(['lawCases',  'contacts', 'notes'])
            ->paginate(15);
        return view('associate.index', ['associates' => $associates]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('associate.create');
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
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'contacts' => 'nullable|array',
            'contacts.*.contact_type' => 'required|string|in:email,phone',
            'contacts.*.contact_value' => 'required|string|max:255',
            'contacts.*.contact_label' => 'nullable|string|max:255',
        ]);

        // Create the client
        $associate = Associate::create([
            'name' => $validatedData['name'],

            'address' => $validatedData['address'] ?? null,
        ]);

        // Store the contacts
        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                $associate->contacts()->create($contact);
            }
        }
        return redirect()->route('associate.show', $associate->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Associate $associate, Request $request)
    {
        if ($request->ajax()) {
            switch ($request->table) {
                case  "cases":
                    return view('components.case.cases-table-default', ['lawCases' => $associate->lawCases(), 'excludeCol' => ['associate']])->render();
                    break;
                case  "notes":
                    return view('components.note.notes-table', ['notes' => $associate->notes()])->render();
                    break;

                default:
                    return '';
                    break;
            }
        }
        $associate->loadCount(['lawCases', 'notes']);
        return view('associate.show', [
            'associate' => $associate,
        ]);
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
        ]);
        $associate->update($validated);
        return response()->json([
            'success' => true,
            'message' => "#$associate->id Associate updated successfully!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Associate $associate, Request $request)
    {
        $this->authorize('delete', Associate::class);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect.',
            ], 422);
        } else {
            if ($associate->lawCases()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete associate because it has existing cases."
                ], 400);
            }
            $associate->delete();
            return response()->json([
                'success' => true,
                'message' => "Associate has been deleted successfully. redirecting in 5 secs",
            ]);
        }
    }
}
