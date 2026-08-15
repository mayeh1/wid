<?php

namespace App\Payments\Drivers;

use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Payments\GatewayNotConfiguredException;
use App\Payments\PaymentGatewayDriver;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class StripeDriver implements PaymentGatewayDriver
{
    public function initiate(Donation $donation, PaymentMethod $method): string
    {
        $client = $this->client($method);

        $recurringInterval = match ($donation->frequency) {
            'monthly' => 'month',
            'annual' => 'year',
            default => null,
        };

        $lineItem = [
            'quantity' => 1,
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => (int) round($donation->amount * 100),
                'product_data' => [
                    'name' => 'Donation to Women in Development, Inc.',
                ],
            ],
        ];

        if ($recurringInterval) {
            $lineItem['price_data']['recurring'] = ['interval' => $recurringInterval];
        }

        $session = $client->checkout->sessions->create([
            'mode' => $recurringInterval ? 'subscription' : 'payment',
            'line_items' => [$lineItem],
            'customer_email' => $donation->donor_email,
            'success_url' => route('donate.success', $donation->receipt_number).'&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('donate.cancel', $donation->receipt_number),
            'metadata' => ['donation_id' => $donation->id, 'receipt_number' => $donation->receipt_number],
        ]);

        $donation->update(['gateway_reference' => $session->id]);

        return $session->url;
    }

    public function verify(Donation $donation, PaymentMethod $method): bool
    {
        if (! $donation->gateway_reference) {
            return false;
        }

        $client = $this->client($method);

        /** @var Session $session */
        $session = $client->checkout->sessions->retrieve($donation->gateway_reference);

        if (in_array($session->payment_status, ['paid', 'no_payment_required'], true)) {
            $donation->markCompleted();

            return true;
        }

        return false;
    }

    private function client(PaymentMethod $method): StripeClient
    {
        $secretKey = $method->config['secret_key'] ?? null;

        if (! $secretKey) {
            throw GatewayNotConfiguredException::forDriver('Stripe');
        }

        return new StripeClient($secretKey);
    }
}
