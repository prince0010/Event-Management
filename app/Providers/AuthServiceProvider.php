<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // THIS CODE CAN BE USED ANYWHERE INSIDE THE CODE BY JUST ACCESSING IT USING THE GATE FACADE
        // When we are using this Gate update-event we are certain that the user is AUTHENTICATED. Thats why we dont need to have to check if the $user model is not null.
       Gate::define('update-auth', function($user, Event $event){
            return $user->id === $event->user_id; // Check if the $user->id is equal to the $event->user_id
       });

    // This Gate would alled delete and it would check if the current user is able to delete attendee from an event

    Gate::define('delete-auth', function($user, Event $event, Attendee $attendee){
    // Check if the current user is the event owner so he can delete any attendee or The user_id alternatively is just the attendee->user_id which would be the person that wants to attend an event.
        return $user->id === $event->user_id || $user->id === $attendee->user_id; 
    });


    }
}
