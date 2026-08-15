<?php

namespace App\Payments\Drivers;

use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Payments\PaymentGatewayDriver;
use RuntimeException;

/**
 * Square's Payments API requires client-side card tokenization via the
 * Web Payments SDK (a card nonce generated in the browser) rather than a
 * simple hosted-checkout redirect, so this driver is a structural stub:
 * the admin can add the method and enter credentials, but completing this
 * integration requires adding the Web Payments SDK to the donation form
 * and posting the resulting nonce to a dedicated endpoint here.
 */
class SquareDriver implements PaymentGatewayDriver
{
    public function initiate(Donation $donation, PaymentMethod $method): string
    {
        throw new RuntimeException('Square requires client-side card tokenization (Web Payments SDK) that has not been wired up yet. Use Stripe, PayPal, Paystack, or Flutterwave, or a manual payment method, in the meantime.');
    }

    public function verify(Donation $donation, PaymentMethod $method): bool
    {
        return false;
    }
}
