<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
{
    use HasFactory;

    // There is always a user associated with the attendee
    // Essentially, this means that one user can attend many events and the other relation is the event() itself.
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event() : BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

}
