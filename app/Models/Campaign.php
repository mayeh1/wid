<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Campaign extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CampaignFactory> */
    use HasFactory, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'target_amount', 'is_published'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'title', 'slug', 'category', 'excerpt', 'description', 'target_amount',
        'start_date', 'end_date', 'updates', 'is_featured', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'updates' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
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

    public function raisedAmount(): float
    {
        return (float) $this->donations()->where('status', 'completed')->sum('amount');
    }

    public function progressPercent(): int
    {
        if ((float) $this->target_amount <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->raisedAmount() / (float) $this->target_amount) * 100));
    }

    public function donorWall()
    {
        return $this->donations()
            ->where('status', 'completed')
            ->where('is_anonymous', false)
            ->latest()
            ->limit(50)
            ->get(['donor_name', 'amount', 'created_at']);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
