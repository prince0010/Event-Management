<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool // You cant type hint those with the user model. You have to make this optional or nullable.
    {
        // Need to return a boolean since everyone can view events.
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    // Individuals can view events. It can be available for guest access. So User $user make this nullable and we'll return true so anyone can view and individual event.
    public function view(?User $user, Event $event): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    // Only authenticated user can see the create events. and we will not make the User $user parameter to nullable. Because if the user is not authenticated he wont be able to create a new event.
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // we get the return in the AuthServiceProvider in the update-auth
        return $user->id === $event->user_id; // Check if the $user->id is equal to the $event->user_id
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // We can also implement the same check for the delete action. 
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        // SOFT DELETE
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        //
    }
}
