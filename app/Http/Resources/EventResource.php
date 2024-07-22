<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            // So User is basically the organizer of the event, and when using this magical when loaded method of the JsonResource, what will happen is this user property would only be --
            // -- present on the response if this user relationship of particular event is loaded.
            'user'=> new UserResource($this->whenLoaded('user')),
            // You can use this whenLoaded() to load one single relation or even a collection of resources. Events has user() and attendees()
            // We use Collection() method because we actually list the attendees and then we can do as well this when loaded attendees. 
            // The attendees here is a static method so no new instance is created. 
            'attendees'=> AttendeesResource::collection($this->whenLoaded('attendees'))
        ];
        
    }
    // php artisan make:resource EventResource

}
