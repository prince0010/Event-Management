<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $events = Event::all();

        // For every user we will make this user attend some random amount of events. 
        foreach($users as $user) {
            // From the events collection, we'll choose a random number of events using the rand() function to generate a random number between 1 and 3. So every user will now select a random
            // number of from one up to three events that this user will attend.   
            $eventsToAttend = $events->random(rand(1, 3));

            foreach ($eventsToAttend as $event){
                // Basically there is no factory for the attend as everything the attendee holds is a relationship to the user which would attend the event and to the actual event. Thus we can --
                // -- pass a simple array where there would be a user ID
                Attendee::create([
                    // We take the user_id as we iterate from the users id 
                    'user_id' => $user->id,
                    // event_id will take from tthe randomly chosen event
                    'event_id' => $event->id
                ]);
            }
        }
    }
}
