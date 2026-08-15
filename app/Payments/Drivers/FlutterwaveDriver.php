<?php

namespace App\Payments\Drivers;

use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Payments\GatewayNotConfiguredException;
use App\Payments\PaymentGatewayDriver;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FlutterwaveDriver implements PaymentGatewayDriver
{
    private const BASE_URL = 'https://api.flutterwave.com/v3';

    public function initiate(Donation $donation, PaymentMethod $method): string
    {
        if ($donation->frequency !== 'one_time') {
            throw new RuntimeException('Recurring donations via Flutterwave require a pre-configured payment plan and are not yet supported.');
        }

        $response = Http::withToken($this->secretKey($method))
            ->post(self::BASE_URL.'/payments', [
                'tx_ref' => $donation->receipt_number,
                'amount' => (string) $donation->amount,
                'currency' => 'USD',
                'redirect_url' => route('donate.success', $donation->receipt_number),
                'customer' => ['email' => $donation->donor_email, 'name' => $donation->donor_name],
                'customizations' => ['title' => 'Donation to Women in Development, Inc.'],
            ])
            ->throw()
            ->json();

        $donation->update(['gateway_reference' => $donation->receipt_number]);

        return $response['data']['link'] ?? throw new RuntimeException('Flutterwave did not return a payment link.');
    }

    public function verify(Donation $donation, PaymentMethod $method): bool
    {
        if (! $donation->gateway_reference) {
            return false;
        }

        $response = Http::withToken($this->secretKey($method))
            ->get(self::BASE_URL.'/transactions/verify_by_reference', ['tx_ref' => $donation->gateway_reference])
            ->json();

        if (($response['data']['status'] ?? null) === 'successful') {
            $donation->markCompleted();

            return true;
        }

        return false;
    }

    private function secretKey(PaymentMethod $method): string
    {
        return $method->config['secret_key'] ?? throw GatewayNotConfiguredException::forDriver('Flutterwave');
    }
}
