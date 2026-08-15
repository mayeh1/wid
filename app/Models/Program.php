<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'title', 'slug', 'icon', 'summary', 'description', 'featured_image',
        'success_story', 'accepts_applications', 'apply_url', 'sort_order', 'status',
    ];

    protected $casts = [
        'accepts_applications' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
