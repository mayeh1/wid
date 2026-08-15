<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentMethod extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PaymentMethodFactory> */
    use HasFactory, InteractsWithMedia;

    /**
     * Built-in gateway drivers the codebase ships with. Admins pick one of
     * these when adding a "gateway" type payment method; "manual" methods
     * (Bank Transfer, CashApp, Zelle, Mobile Money, Crypto, Custom) need no
     * driver at all and are fully admin-configurable without any code.
     */
    public const DRIVERS = [
        'stripe' => 'Stripe',
        'paypal' => 'PayPal',
        'flutterwave' => 'Flutterwave',
        'paystack' => 'Paystack',
        'square' => 'Square',
        'authorize_net' => 'Authorize.net',
    ];

    protected $fillable = [
        'name', 'slug', 'type', 'driver', 'config', 'instructions', 'is_enabled', 'order',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'encrypted:array',
            'is_enabled' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function logoUrl(): ?string
    {
        return $this->getFirstMediaUrl('logo') ?: null;
    }

    public function isGateway(): bool
    {
        return $this->type === 'gateway';
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }
}
