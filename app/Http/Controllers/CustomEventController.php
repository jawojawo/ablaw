<?php

namespace App\Http\Controllers;

use App\Models\CustomEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->input('type');
        $title = $request->input('title');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $customEventsQuery = CustomEvent::when($type, function ($query, $type) {
            $query->where('type', 'like', "%{$type}%");
        })
            ->when($title, function ($query, $title) {
                $query->where('title', 'like', "%{$title}%");
            })
            ->when($start_time, function ($query, $start_time) {
                $query->whereDate('start_time', '>=', $start_time);
            })
            ->when($end_time, function ($query, $end_time) {
                $query->whereDate('end_time', '<=', $end_time);
            })
            ->withCount(['notes'])->with(['user']);

        $types = CustomEvent::select('type')->distinct()->limit(100)->pluck('type');
        $minDate = $customEventsQuery->min('start_time');
        $maxDate = $customEventsQuery->max('end_time');
        $ongoingCount =  (clone $customEventsQuery)->where('start_time', '<=',  now())->where('end_time', '>=',  now())->count();
        $upcomingCount =  (clone $customEventsQuery)->where('start_time', '>',  now())->count();
        $pastCount =  (clone $customEventsQuery)->where('end_time', '<=',  now())->count();
        $customEvents = $customEventsQuery->sortByEventStatus()->paginate(15);
        return view('custom-event.index', [
            'customEvents' => $customEvents,
            'types' => $types,
            'minDate' => $minDate,
            'maxDate' => $maxDate,
            'ongoingCount' => $ongoingCount,
            'upcomingCount' => $upcomingCount,
            'pastCount' => $pastCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = CustomEvent::select('type')->distinct()->limit(100)->pluck('type');
        return view('custom-event.create', ['types' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'start_time' => 'date|required',
            'end_time' => 'date|required|after_or_equal:start_time',
            'description' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:500',
        ]);
        $customEvent = CustomEvent::create($validatedData);
        return redirect()->route('customEvent.show', $customEvent->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomEvent $customEvent, Request $request)
    {
        $types = CustomEvent::select('type')->distinct()->limit(100)->pluck('type');
        if ($request->ajax()) {
            switch ($request->table) {
                case  "notes":
                    return view('components.note.notes-table', ['notes' => $customEvent->notes()])->render();
                    break;

                default:
                    return '';
                    break;
            }
        }
        $customEvent->loadCount(['notes']);
        return view('custom-event.show', ['customEvent' => $customEvent, 'types' => $types]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomEvent $customEvents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomEvent $customEvent)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'start_time' => 'date|required',
            'end_time' => 'date|required|after_or_equal:start_time',
            'description' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:500',
        ]);
        $customEvent->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => "#$customEvent->id Custom Event updated successfully!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomEvent $customEvent, Request $request)
    {
        $this->authorize('delete', CustomEvent::class);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect.',
            ], 422);
        } else {
            $customEvent->delete();
            return response()->json([
                'success' => true,
                'message' => "Custom Event has been deleted successfully. redirecting in 5 secs",
            ]);
        }
    }
}
