<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    public const CATEGORIES = [
        'book' => 'Book',
        'magazine' => 'Magazine',
        'consultancy_guide' => 'Consultancy Guide',
        'toolkit' => 'Toolkit',
        'training_course' => 'Training Course',
        'report' => 'Report',
        'other' => 'Other',
    ];

    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'price',
        'compare_price', 'type', 'category', 'stock', 'is_published',
        'is_featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'is_published', 'stock'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            $product->slug ??= Str::slug($product->name);
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('download')->singleFile();
    }

    public function imageUrl(): ?string
    {
        return $this->getFirstMediaUrl('image') ?: null;
    }

    public function downloadUrl(): ?string
    {
        return $this->getFirstMediaUrl('download') ?: null;
    }

    public function isDigital(): bool
    {
        return $this->type === 'digital';
    }

    public function inStock(): bool
    {
        if ($this->isDigital()) {
            return true;
        }

        return $this->stock === null || $this->stock > 0;
    }

    public function formattedPrice(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function hasDiscount(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
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
