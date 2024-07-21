<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'start_time', 'end_time', 'user_id'];
    // Add Return Types : BelongsTo
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     // Add Return Types : HasMany
    // Event is the Owner of the Attendees
    public function attendees() : HasMany
    {
        return $this->hasMany(Attendee::class);
    }

}
