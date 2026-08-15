<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'description', 'starts_at', 'ends_at',
        'location', 'google_maps_embed_url', 'registration_url', 'capacity', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
    }

    public function featuredImageUrl(): ?string
    {
        return $this->getFirstMediaUrl('featured_image') ?: null;
    }

    public function isPast(): bool
    {
        return $this->starts_at->isPast();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now())->orderBy('starts_at');
    }

    public function scopePast($query)
    {
        return $query->where('starts_at', '<', now())->orderByDesc('starts_at');
    }
}
