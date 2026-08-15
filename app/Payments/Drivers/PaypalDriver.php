<?php

namespace App\Payments\Drivers;

use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Payments\GatewayNotConfiguredException;
use App\Payments\PaymentGatewayDriver;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * PayPal Orders v2 REST integration (one-time donations only). Recurring
 * donations via PayPal require pre-created Subscription Plans in the
 * merchant's PayPal account, which is an operational setup step beyond
 * what can be wired generically here — monthly/annual PayPal donations
 * are intentionally unsupported until that's configured.
 */
class PaypalDriver implements PaymentGatewayDriver
{
    public function initiate(Donation $donation, PaymentMethod $method): string
    {
        if ($donation->frequency !== 'one_time') {
            throw new RuntimeException('Recurring donations via PayPal require a pre-configured Subscription Plan and are not yet supported.');
        }

        $token = $this->accessToken($method);
        $baseUrl = $this->baseUrl($method);

        $response = Http::withToken($token)->post("{$baseUrl}/v2/checkout/orders", [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($donation->amount, 2, '.', ''),
                ],
                'description' => 'Donation to Women in Development, Inc.',
                'custom_id' => $donation->receipt_number,
            ]],
            'application_context' => [
                'return_url' => route('donate.success', $donation->receipt_number),
                'cancel_url' => route('donate.cancel', $donation->receipt_number),
            ],
        ])->throw()->json();

        $donation->update(['gateway_reference' => $response['id']]);

        $approveLink = collect($response['links'])->firstWhere('rel', 'approve');

        return $approveLink['href'] ?? throw new RuntimeException('PayPal did not return an approval link.');
    }

    public function verify(Donation $donation, PaymentMethod $method): bool
    {
        if (! $donation->gateway_reference) {
            return false;
        }

        $token = $this->accessToken($method);
        $baseUrl = $this->baseUrl($method);

        $response = Http::withToken($token)
            ->post("{$baseUrl}/v2/checkout/orders/{$donation->gateway_reference}/capture")
            ->json();

        if (($response['status'] ?? null) === 'COMPLETED') {
            $donation->markCompleted();

            return true;
        }

        return false;
    }

    private function accessToken(PaymentMethod $method): string
    {
        $clientId = $method->config['client_id'] ?? null;
        $secret = $method->config['secret'] ?? null;

        if (! $clientId || ! $secret) {
            throw GatewayNotConfiguredException::forDriver('PayPal');
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post($this->baseUrl($method).'/v1/oauth2/token', ['grant_type' => 'client_credentials'])
            ->throw()
            ->json();

        return $response['access_token'];
    }

    private function baseUrl(PaymentMethod $method): string
    {
        $sandbox = $method->config['sandbox'] ?? true;

        return $sandbox ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
    }
}
