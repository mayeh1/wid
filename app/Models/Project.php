<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'category', 'status', 'excerpt', 'description',
        'budget', 'raised', 'location', 'start_date', 'end_date',
        'progress_percent', 'timeline', 'is_featured', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'timeline' => 'array',
            'budget' => 'decimal:2',
            'raised' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function featuredImageUrl(): ?string
    {
        return $this->getFirstMediaUrl('featured_image') ?: null;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
