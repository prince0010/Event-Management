<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    // Every Event needs to be owned by a user, Every event needs to be tied to a specific user, an owner and organizer of that event.
        
    // Get all the users from the database
        $users = User::all();

        // 200 events
        for ($i = 0; $i <= 200; $i++)
        {
            // The users will return a collections an object in Laravel.
            $user = $users->random();
            // Call create() on the collection and pass in an array of data to create a new event.
            // Call create() the new model and store it inside the database, incase on this one we need to pass the userID and this would be the ID of the user we've just chosen by getting a random user.
            \App\Models\Event::factory()->create([
                    'user_id' => $user->id
               ]);
        }   
    
    }
}
