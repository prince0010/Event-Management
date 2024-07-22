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
        $query = Event::query(); // Construct query in the first line by using the event query method. This will start a QUERY BUILDER for this event MODEL.
        // It needs to be explicit about what relations can be loaded with the events.
        // It allows to load the ['user', 'attendees', 'attendees.user']
        $relations = ['user', 'attendees', 'attendees.user'];

        // Supplementing this query with something optionally. we used foreach loop to over all the relations that we have inside our array.
        // 
        foreach($relations as $relation){
            $query->when(  //Every query builder instance has this when method. When the first argument passed to this, when method is true, it will run the second function which can alter the query. 
                $this->shouldIncludeRelation($relation), // If this true the call the arrow function fn()
                fn($q) => $q->with($relation) // If this true the call the arrow function fn()
            );
        }

        // return EventResource::collection(Event::all());
        // This will be loading all the events together in the database with the user relationship
        return EventResource::collection(
          $query->latest()->paginate() // and we use paginate to paginate the lists of the events
    );
    }

    // It should tell us if the specific relation should be included or not.
    // this relation that was passeed as an argument should not be included in return false !$include
    protected function shouldIncludeRelation(string $relation) : bool
    {
        // We will get the request query 
        // In laravel you can get the current request using the request function
        $include = request()->query('include');

        // If the parameter is null or well emplty we can check if its true or false.
        // So it will return false if include would be null
        if(!$include){
            return false;
        }
        
        // We'll use the built in PHP explode function that lets you convert a string to an array using a specific separator
        // IN THIS CASE we will use the comma as the separator ','
        // the array_map will make a run through every results in the url in ?include in url that explode would generate through a trim function.
        //  
        $relations = array_map('trim', explode(',', $include)); // trim is a built in php function that will remove all the starting leading spaces and all the ending spaces from any string.

        // dd($relations);
        return in_array($relation, $relations); // So it checks if a specific relation that's passed to this method is inside relations array.
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
