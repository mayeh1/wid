<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Payments\Drivers\PaypalDriver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaypalDriverTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_captures_the_order_with_a_json_content_type_and_marks_donation_completed(): void
    {
        Http::fake([
            '*/v1/oauth2/token' => Http::response(['access_token' => 'fake-token'], 200),
            '*/v2/checkout/orders/*/capture' => Http::response(['status' => 'COMPLETED'], 201),
        ]);

        $method = PaymentMethod::factory()->create([
            'type' => 'gateway',
            'driver' => 'paypal',
            'config' => ['client_id' => 'cid', 'secret' => 'csecret', 'sandbox' => true],
        ]);

        $donation = Donation::factory()->create([
            'payment_method_id' => $method->id,
            'status' => 'pending',
            'gateway_reference' => 'ORDER123',
        ]);

        $result = (new PaypalDriver)->verify($donation, $method);

        $this->assertTrue($result);
        $this->assertSame('completed', $donation->fresh()->status);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), '/capture')) {
                return true;
            }

            return $request->hasHeader('Content-Type', 'application/json');
        });
    }

    public function test_verify_returns_false_when_paypal_does_not_report_completed(): void
    {
        Http::fake([
            '*/v1/oauth2/token' => Http::response(['access_token' => 'fake-token'], 200),
            '*/v2/checkout/orders/*/capture' => Http::response(['status' => 'VOIDED'], 200),
        ]);

        $method = PaymentMethod::factory()->create([
            'type' => 'gateway',
            'driver' => 'paypal',
            'config' => ['client_id' => 'cid', 'secret' => 'csecret', 'sandbox' => true],
        ]);

        $donation = Donation::factory()->create([
            'payment_method_id' => $method->id,
            'status' => 'pending',
            'gateway_reference' => 'ORDER123',
        ]);

        $result = (new PaypalDriver)->verify($donation, $method);

        $this->assertFalse($result);
        $this->assertSame('pending', $donation->fresh()->status);
    }
}
