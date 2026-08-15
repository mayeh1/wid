<?php

namespace App\Payments\Drivers;

use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Payments\GatewayNotConfiguredException;
use App\Payments\PaymentGatewayDriver;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaystackDriver implements PaymentGatewayDriver
{
    private const BASE_URL = 'https://api.paystack.co';

    public function initiate(Donation $donation, PaymentMethod $method): string
    {
        if ($donation->frequency !== 'one_time') {
            throw new RuntimeException('Recurring donations via Paystack require a pre-configured Plan and are not yet supported.');
        }

        $response = Http::withToken($this->secretKey($method))
            ->post(self::BASE_URL.'/transaction/initialize', [
                'email' => $donation->donor_email,
                'amount' => (int) round($donation->amount * 100),
                'currency' => 'USD',
                'reference' => $donation->receipt_number,
                'callback_url' => route('donate.success', $donation->receipt_number),
            ])
            ->throw()
            ->json();

        $donation->update(['gateway_reference' => $response['data']['reference'] ?? $donation->receipt_number]);

        return $response['data']['authorization_url'] ?? throw new RuntimeException('Paystack did not return an authorization URL.');
    }

    public function verify(Donation $donation, PaymentMethod $method): bool
    {
        if (! $donation->gateway_reference) {
            return false;
        }

        $response = Http::withToken($this->secretKey($method))
            ->get(self::BASE_URL."/transaction/verify/{$donation->gateway_reference}")
            ->json();

        if (($response['data']['status'] ?? null) === 'success') {
            $donation->markCompleted();

            return true;
        }

        return false;
    }

    private function secretKey(PaymentMethod $method): string
    {
        return $method->config['secret_key'] ?? throw GatewayNotConfiguredException::forDriver('Paystack');
    }
}
