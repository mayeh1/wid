<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id', 'name', 'email', 'phone', 'guests', 'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
