<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return EventResource::collection(Event::all());

        // This will be loading all the events together in the database with the user relationship
        return EventResource::collection(Event::with('user', 'attendees')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = Event::create([
            ...$request->validate([
                'name'=> 'required|string|max:255',
                'description'=>'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // load the user in the event
        $event->load('user', 'attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
      $event->update(
        $request->validate([
            'name'=> 'sometimes|string|max:255',
            'description'=>'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time'
        ]));

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
    
        // So when delete a resource, we should not be returning the resource because it doesn't exist anymore.
        // In latest versions of PHP, you can use named parameters, so we can just do status for no content is 204. = status: 204
        // REMEMBER: You dont send any body with the response when you pass the status as 204 because it means that no content is available. And a common practice if you delete resources
        return response(status: 204);
        
        // Old version of php which doesn't support named parameters is like this:
        // return response(' ' , 204);
    }
}
