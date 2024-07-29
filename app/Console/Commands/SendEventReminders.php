<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Notification to all Event Attendees that the Event starts soon.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Logic here that will be responsible for the commands

        // Query that set the event reminder variable
        $events = \App\Models\Event::with('attendees.user') // We want the attendees user which would be pointing to the actual user who attends the event. 
        // So we can do such nested eager loading of relationships 'attendees.user' and with that it will not only load the user so adding the user, but also all the attendee models.
        ->whereBetween('start_time', [now(), now()->addDay()])->get();     // Filtering of the Events that will start in the next 24 hours
        // now() is a helper function for the carbon dates. It will just create a carbon date object with the current time. 
        
        $eventCount = $events->count(); //count will just return on how many items in the collection $event 
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found {$eventCount} {$eventLabel}."); // Will get the events starting from the current time and up to one day in the future: example '2024-07-29 15:00:00', '2024-07-30 15:00:00' 

        // Now we can get easy access to all the attendees from those events and notify them about the event.
        // Iterate over all the events inside this events collection and to tell every single attendee that attends a particular event that the event is due in 24 hours.  
        // List of all attendees. This $events would be a type of collection, which is just a wrapper around arrays, so it has those  handy methods that lets you define some closure function that you can run on every event inside this collection.
      
        $events->each(// We iterate all over the events, using each method, which will run --
            fn($event)=> $event->attendees->each( //-- this function fn() for every single event. -> once we get to every single $event , every single $event has got attendees and attendees '$event->attendees' is also a collection. -> so it also has each method which --
            fn($attendee) => $this->info("Notifying the user {$attendee->user->id}"))); // -- lets us run this function for every single attendee of every single event. Finally we run this info which will output this message for every attendee of every event. 
       
            $this->info('Reminders Notification Sent Successfully!'); 
    }
}
