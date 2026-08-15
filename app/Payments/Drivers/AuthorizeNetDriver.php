<?php

namespace App\Payments\Drivers;

use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Payments\PaymentGatewayDriver;
use RuntimeException;

/**
 * Authorize.net's Accept.js also requires client-side card tokenization
 * before a charge can be submitted server-side, so — like Square — this
 * driver is a structural stub ready for that frontend work.
 */
class AuthorizeNetDriver implements PaymentGatewayDriver
{
    public function initiate(Donation $donation, PaymentMethod $method): string
    {
        throw new RuntimeException('Authorize.net requires client-side tokenization (Accept.js) that has not been wired up yet. Use Stripe, PayPal, Paystack, or Flutterwave, or a manual payment method, in the meantime.');
    }

    public function verify(Donation $donation, PaymentMethod $method): bool
    {
        return false;
    }
}
