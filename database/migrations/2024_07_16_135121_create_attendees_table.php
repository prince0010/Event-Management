<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();

            // One to many relationship
            // Attendees need a one relationship is the user
            // So to attend an event, you need to be registered, you need to have an account and this would be a --
            // -- relationship to the user, one to many relationship as well, which means that user can attend many events
            $table->foreignIdFor(User::class);
            // Relationship with the event so we would specify which event this user attends.
            $table->foreignIdFor(Event::class);

            // This will make the time for when will this created or updated.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};
