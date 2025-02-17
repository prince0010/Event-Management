<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeesResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    public function __construct(){
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
        $this->middleware('throttle:60,1')->only(['store', 'destroy']); // This would apply this throttling to every single action, but maybe we should not be so limiting for the actions that just read data because throttling is best used for public facing write heavy actions. 
        // Basically, this means endpoints that create, update or delete resources. So you should definitely apply throttling for those actions, especially if they are public so they dont require authentication.
        // Well, its a different case when to create or update something in your API, you need to be authenticated because you know, then you can track malicious users and maybe block them anyway.
        $this->authorizeResource(Attendee::class, 'attendee'); // Attendee is the model class and the attendee is the route and you can check the routes in the models to be added in this parameter in php artisan route:list 
    }

    private array $relations = ['user', 'event']; // We have a field here defined just the user. THATS THE ONLY RELATIONSHIPS IN THE ATTENDEE.
    // private array $relations = ['user']; // EITHER YOU CAN ADD THE EVENT OR NOT FOR THE INCLUDE IN THE URL ENDPOINT LINK

  

    // List all the attendees of an specific event
    public function index(Event $event)
    {
        $attendees = $this->loadRelationships( // We have to call loadRelationships() one a query
            $event->attendees()->latest()
            // We are getting the attendees() from the event and if you open the Event MOdel and take a look at the attendees it would return something would be of type HasMany. Thats the class
            // and you must add the HasMany in the loadRelationships() method asa supported type and as an another return type otherwise we'll get an error and also make sure to import that hasMany class in the trait file which is the CanLoadRelationships.php.
        );

        return AttendeesResource::collection(
            $attendees->paginate()
        );
        
    }

    public function store(Request $request, Event $event)
    {
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                // 'user_id' => $request->user()->id
                'user_id' => $request->user()->id
            ])
        );

        return new AttendeesResource($attendee);
    }

    public function show(Event $event, Attendee $attendee)
    {
        // This attendee would be fetched the way that it will be scoped to an event.
        return new AttendeesResource(
            $this->loadRelationships($attendee)
        ); // we are wrapping the model that's fetched using the route model binding with the loadRelationships() method.
    }

    public function destroy(Event $event, Attendee $attendee)
    {
        // $this->authorize('delete-auth', [$event, $attendee]);
        $attendee->delete();

        return response(status: 204);

    }
}
