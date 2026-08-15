<?php

namespace App\Payments;

use App\Models\Donation;
use App\Models\PaymentMethod;

/**
 * Contract every gateway-backed payment method (Stripe, PayPal, etc.)
 * implements. Manual methods (bank transfer, CashApp, mobile money, ...)
 * never touch this — they're purely admin-configured instruction text and
 * create a pending Donation directly, with zero code per method.
 */
interface PaymentGatewayDriver
{
    /**
     * Start a checkout for the given donation and return a URL the donor
     * should be redirected to in order to complete payment.
     */
    public function initiate(Donation $donation, PaymentMethod $method): string;

    /**
     * Confirm (server-side) that a checkout actually completed successfully,
     * called from the gateway's webhook or the donor's return redirect.
     * Implementations should be idempotent and update the Donation status.
     */
    public function verify(Donation $donation, PaymentMethod $method): bool;
}
