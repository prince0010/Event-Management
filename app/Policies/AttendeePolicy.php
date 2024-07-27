<?php

namespace App\Policies;

use App\Models\Attendee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttendeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Attendee $attendee): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */

    //  Since everyone can create attendee and this one needs to be authenticated theres no need to make the User $user be nullable.
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attendee $attendee): bool
    {
         // Check if the current user is the event owner so he can delete any attendee or The user_id alternatively is just the attendee->user_id which would be the person that wants to attend an event.
         return $user->id === $attendee->event->user_id || // This particular policy there's no access in the event here we can do $attendee->event->user_id. Basically we can access the related ->event-> and it will be fetched from the database
         $user->id === $attendee->user_id; 
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attendee $attendee): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attendee $attendee): bool
    {
        //
    }
}
