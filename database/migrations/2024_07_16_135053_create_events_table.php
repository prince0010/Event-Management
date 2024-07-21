<?php

use App\Models\User;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

        // Create a one to many relationship between the user and events
        // One to many users in the events, one of the user can be owner or administrator of many events
        // the column specifying the relationship goes into the events table. So basically User owns Events.

        $table->foreignIdFor(User::class);
        $table->string('name');
        $table->text('description')->nullable();

        // Add a start time to know when itll get start og updated and end to know when will it end the events
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
