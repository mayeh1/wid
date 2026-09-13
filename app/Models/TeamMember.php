<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TeamMember extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\TeamMemberFactory> */
    use HasFactory, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'role_title', 'category', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'user_id', 'name', 'slug', 'role_title', 'category', 'bio', 'email', 'linkedin_url',
        'portfolio_url', 'website_url', 'twitter_url', 'facebook_url', 'instagram_url',
        'order', 'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function photoUrl(): ?string
    {
        return $this->getFirstMediaUrl('photo') ?: null;
    }

    /**
     * Social/external links keyed by a label and icon hint, skipping any that aren't filled in.
     */
    public function links(): array
    {
        return array_filter([
            'website' => $this->website_url,
            'portfolio' => $this->portfolio_url,
            'linkedin' => $this->linkedin_url,
            'twitter' => $this->twitter_url,
            'facebook' => $this->facebook_url,
            'instagram' => $this->instagram_url,
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
