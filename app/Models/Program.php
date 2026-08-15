<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Program extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ProgramFactory> */
    use HasFactory, InteractsWithMedia;

    public const CATEGORIES = [
        'employment' => 'Employment Programs',
        'entrepreneurship' => 'Entrepreneurship',
        'financial_literacy' => 'Financial Literacy',
        'leadership_development' => 'Leadership Development',
        'mentorship' => 'Mentorship',
        'scholarships' => 'Scholarships',
        'community_development' => 'Community Development',
        'emergency_assistance' => 'Emergency Assistance',
        'womens_empowerment' => "Women's Empowerment",
    ];

    protected $fillable = [
        'title', 'slug', 'category', 'icon', 'excerpt', 'description', 'success_stories',
        'apply_url', 'is_featured', 'is_published', 'order',
    ];

    protected function casts(): array
    {
        return [
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
