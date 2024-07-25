<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
   use CanLoadRelationships;

//    Reuse those relations definition on every action so I Dont have to set this array in every single action, you can also add it as a field.
    // It should tell us if the specific relation should be included or not.
    // this relation that was passeed as an argument should not be included in return false !$include
    private array $relations = ['user', 'attendees', 'attendees.user'];

    public function index()
    {
         // It needs to be explicit about what relations can be loaded with the events.
        // It allows to load the ['user', 'attendees', 'attendees.user']
        $query = $this->loadRelationships(Event::query()); // Construct query in the first line by using the event query method. This will start a QUERY BUILDER for this event MODEL.
       

        // return EventResource::collection(Event::all());
        // This will be loading all the events together in the database with the user relationship
        return EventResource::collection(
          $query->latest()->paginate() // and we use paginate to paginate the lists of the events
    );
    }


    
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

        return new EventResource($this->loadRelationships($event)); // This is actually a loaded model becase we just created 
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // load the user in the event
        // $event->load('user', 'attendees');

        // So instead of laoding random relationships ->  $event->load('user', 'attendees');, we can just do this loadRelationships() event or you can skip using the route --
        // -- model binding and insteald you can just use the event findOrFail() method and then wrap it with the loadRelationships()
        return new EventResource($this->loadRelationships($event));
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

        return new EventResource($this->loadRelationships($event));
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
