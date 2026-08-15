<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    /** @use HasFactory<\Database\Factories\VolunteerFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'phone', 'skills', 'availability', 'bio', 'status', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'approved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hourLogs()
    {
        return $this->hasMany(VolunteerHourLog::class);
    }

    public function totalHours(): float
    {
        return (float) $this->hourLogs()->sum('hours');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
