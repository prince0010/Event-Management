<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeesResource;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    // List all the attendees of an specific event
    public function index(Event $event)
    {
        $attendees = $event->attendees()->latest();

        return AttendeesResource::collection(
            $attendees->paginate()
        );
        
    }

    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            // 'user_id' => $request->user()->id
            'user_id' => 1
        ]);

        return new AttendeesResource($attendee);
    }

    public function show(Event $event, Attendee $attendee)
    {
        // This attendee would be fetched the way that it will be scoped to an event.
        return new AttendeesResource($attendee);
    }

   
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $event, Attendee $attendee)
    {

        $attendee->delete();


        return response(status: 204);

    }
}
