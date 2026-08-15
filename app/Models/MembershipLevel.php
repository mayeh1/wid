<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipLevel extends Model
{
    /** @use HasFactory<\Database\Factories\MembershipLevelFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'annual_price', 'perks', 'is_active', 'order',
    ];

    protected function casts(): array
    {
        return [
            'annual_price' => 'decimal:2',
            'perks' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
