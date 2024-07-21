<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // THIS IS THE SPECIFIC ORDER BY THIS SEEDER
        // 1. LOAD SOME USERS 
        // 2. GENERATE SOME EVENTS WITH A RANDOM OWNER
        // 3. GENERATE SOME ATTENDEES FOR THE EVENTS, EVERY USER ATTENDS A RANDOM AMOUNT OF 1-3 EVENTS.

        \App\Models\User::factory(1000)->create();

        // Call the seeders we need to call it by specifc order
        
        $this->call(EventSeeder::class);
        $this->call(AttendeeSeeder::class);


    
    }
}
