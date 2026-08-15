<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerHourLog extends Model
{
    protected $fillable = ['volunteer_id', 'date', 'hours', 'activity'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'hours' => 'decimal:2',
        ];
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }
}
