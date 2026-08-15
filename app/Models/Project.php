<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'location', 'featured_image', 'summary', 'description',
        'budget', 'raised', 'project_status', 'start_date', 'end_date', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'raised' => 'decimal:2',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function progressPercent(): int
    {
        if (! $this->budget || (float) $this->budget <= 0) {
            return 0;
        }

        return (int) min(100, round(((float) $this->raised / (float) $this->budget) * 100));
    }
}
