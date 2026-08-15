<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name', 'tagline', 'mission_statement', 'ein', 'founder_name',
        'contact_email', 'contact_phone', 'address', 'office_hours',
        'facebook_url', 'instagram_url', 'linkedin_url', 'twitter_url', 'youtube_url',
        'google_maps_embed_url',
        'donations_stat_label', 'women_empowered_count', 'scholarships_awarded_count',
        'communities_reached_count', 'projects_completed_count',
        'meta_title', 'meta_description',
    ];

    /**
     * The site has exactly one settings row; create it on first access.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
