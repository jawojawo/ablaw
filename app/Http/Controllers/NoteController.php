<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(string $notableType, $notableId)
    {
        $notableTypes = config('enums.notableTypes', []);

        if ($notableType === 'all' && $notableId === 'all') {
            $notes = Note::with('user')->orderBy('updated_at', 'desc')->paginate(10);
            return response()->json([
                'success' => true,
                'data' => $notes
            ]);
        }
        if (!array_key_exists($notableType, $notableTypes)) {
            abort(404, 'Notable type not found.');
        }
        $modelClass = $notableTypes[$notableType];

        if ($notableId === 'all') {
            $notes = Note::with('user')->where('notable_type', $modelClass)->orderBy('updated_at', 'desc')->paginate(10);
        } else {
            $notable = $modelClass::findOrFail($notableId);
            $notes = $notable->notes()->with('user')->orderBy('updated_at', 'desc')->paginate(10);
        }
        return response()->json([
            'success' => true,
            'data' => $notes
        ]);
    }

    public function store(Request $request, string $notableType, int $notableId)
    {
        $notableTypes = config('enums.notableTypes', []);
        if (!array_key_exists($notableType, $notableTypes)) {
            abort(404, 'Invalid notable type.');
        }
        $validatedData = $request->validate([
            'note' => 'required|string|max:500',
        ]);

        $modelClass = $notableTypes[$notableType];
        $notable = $modelClass::findOrFail($notableId);
        $note = $notable->notes()->create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Note created successfully!',
            'note' => $note,
        ]);
    }

    // public function storeClient(Client $client, Request $request)
    // {
    //     $validated = $request->validate([
    //         'note' => 'required|string|max:255',
    //     ]);

    //     $contact = $client->notes()->create($validated);

    //     return response()->json([
    //         'success' => true,
    //         'message' => "Note successfully created",
    //     ]);
    // }

    public function update(Note $note, Request $request)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:500',
        ]);
        $note->update($validated);
        return response()->json([
            'success' => true,
            'message' => "#$note->id Note successfully updated",
        ]);
    }
    public function delete(Note $note)
    {
        $id = $note->id;
        $note->delete();
        return response()->json([
            'success' => true,
            'message' => "#$id Note deleted successfully!"
        ]);
    }
}
