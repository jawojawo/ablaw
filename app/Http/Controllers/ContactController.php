<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\Client;
use App\Models\Contact;
use App\Models\CourtBranch;
use App\Models\LawCase;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // public function indexClient(Client $client)
    // {
    //     $contacts = $client->contacts;
    //     return response()->json([
    //         'success' => true,
    //         'data' => $contacts,
    //     ]);
    // }
    // public function indexCase(LawCase $lawCase)
    // {
    //     $contacts = $lawCase->contacts;
    //     return response()->json([
    //         'success' => true,
    //         'data' => $contacts,
    //     ]);
    // }
    // public function storeClient(Client $client, Request $request)
    // {
    //     $validated = $request->validate([
    //         'contact_type' => 'required|string|in:email,phone',
    //         'contact_value' => 'required|string|max:255',
    //         'contact_label' => 'nullable|string|max:255',
    //     ]);

    //     $contact = $client->contacts()->create($validated);

    //     return response()->json([
    //         'success' => true,
    //         'message' => "Contact successfully created",
    //     ]);
    // }
    // public function storeCase(LawCase $lawCase, Request $request)
    // {
    //     $validated = $request->validate([
    //         'contact_type' => 'required|string|in:email,phone',
    //         'contact_value' => 'required|string|max:255',
    //         'contact_label' => 'nullable|string|max:255',
    //     ]);

    //     $contact = $lawCase->contacts()->create($validated);

    //     return response()->json([
    //         'success' => true,
    //         'message' => "Contact successfully created",
    //     ]);
    // }
    // public function indexAssociate(Associate $associate)
    // {
    //     $contacts = $associate->contacts;
    //     return response()->json([
    //         'success' => true,
    //         'data' => $contacts,
    //     ]);
    // }
    // public function storeAssociate(Associate $associate, Request $request)
    // {
    //     $validated = $request->validate([
    //         'contact_type' => 'required|string|in:email,phone',
    //         'contact_value' => 'required|string|max:255',
    //         'contact_label' => 'nullable|string|max:255',
    //     ]);

    //     $contact = $associate->contacts()->create($validated);

    //     return response()->json([
    //         'success' => true,
    //         'message' => "Contact successfully created",
    //     ]);
    // }
    // public function indexCourtBranch(CourtBranch $courtBranch)
    // {
    //     $contacts = $courtBranch->contacts;
    //     return response()->json([
    //         'success' => true,
    //         'data' => $contacts,
    //     ]);
    // }
    // public function storeCourtBranch(CourtBranch $courtBranch, Request $request)
    // {
    //     $validated = $request->validate([
    //         'contact_type' => 'required|string|in:email,phone',
    //         'contact_value' => 'required|string|max:255',
    //         'contact_label' => 'nullable|string|max:255',
    //     ]);

    //     $contact = $courtBranch->contacts()->create($validated);

    //     return response()->json([
    //         'success' => true,
    //         'message' => "Contact successfully created",
    //     ]);
    // }

    public function index(string $contactableType, $contactableId)
    {
        $contactableTypes = config('enums.contactableTypes', []);

        if ($contactableType === 'all' && $contactableId === 'all') {
            $contacts = Contact::orderBy('updated_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $contacts
            ]);
        }
        if (!array_key_exists($contactableType, $contactableTypes)) {
            abort(404, 'Contactable type not found.');
        }
        $modelClass = $contactableTypes[$contactableType];

        if ($contactableId === 'all') {
            $contacts = Contact::where('contactable_type', $modelClass)->orderBy('updated_at', 'desc')->get();
        } else {
            $contactable = $modelClass::findOrFail($contactableId);
            $contacts = $contactable->contacts()->orderBy('updated_at', 'desc')->get();
        }

        //return ($contacts->get()->toJson());
        return response()->json([
            'success' => true,
            'data' => $contacts
        ]);
    }

    public function store(Request $request, string $contactableType, int $contactableId)
    {
        $contactableTypes = config('enums.contactableTypes', []);
        if (!array_key_exists($contactableType, $contactableTypes)) {
            abort(404, 'Invalid notable type.');
        }
        $validatedData = $request->validate([
            'contact_type' => 'required|string|in:email,phone',
            'contact_value' => 'required|string|max:255',
            'contact_label' => 'nullable|string|max:255',
        ]);

        $modelClass = $contactableTypes[$contactableType];
        $contactable = $modelClass::findOrFail($contactableId);
        $contact = $contactable->contacts()->create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully!',
            'contact' => $contact,
        ]);
    }

    public function update(Contact $contact, Request $request)
    {
        $validated = $request->validate([
            'contact_type' => 'required|string|in:email,phone',
            'contact_value' => 'required|string|max:255',
            'contact_label' => 'nullable|string|max:255',
        ]);
        $contact->update($validated);
        return response()->json([
            'success' => true,
            'message' => "#$contact->id Contact successfully updated",
        ]);
    }
    public function delete(Contact $contact)
    {
        $id = $contact->id;
        $contact->delete();
        return response()->json([
            'success' => true,
            'message' => "#$id Contact deleted successfully!"
        ]);
    }
}
