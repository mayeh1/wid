<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HeroSlide extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\HeroSlideFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'heading', 'subheading', 'cta_label', 'cta_url', 'is_published', 'order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('background')->singleFile();
    }

    public function backgroundUrl(): ?string
    {
        return $this->getFirstMediaUrl('background') ?: null;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
