<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GalleryAlbum extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\GalleryAlbumFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'description', 'type', 'is_published', 'order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('photos');
    }

    public function coverUrl(): ?string
    {
        return $this->getFirstMediaUrl('cover') ?: $this->getFirstMediaUrl('photos') ?: null;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
