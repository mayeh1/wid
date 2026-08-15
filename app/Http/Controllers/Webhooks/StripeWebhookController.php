<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Mail\DonationReceiptMail;
use App\Models\Donation;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $method = PaymentMethod::where('driver', 'stripe')->where('type', 'gateway')->first();
        $webhookSecret = $method?->config['webhook_secret'] ?? null;

        try {
            $event = $webhookSecret
                ? Webhook::constructEvent($request->getContent(), $request->header('Stripe-Signature'), $webhookSecret)
                : json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);
        } catch (UnexpectedValueException|\Throwable $e) {
            Log::warning('Invalid Stripe webhook payload', ['error' => $e->getMessage()]);

            return response('Invalid payload', 400);
        }

        $type = is_object($event) && isset($event->type) ? $event->type : null;

        if (in_array($type, ['checkout.session.completed', 'invoice.payment_succeeded'], true)) {
            $sessionId = $event->data->object->id ?? null;
            $donationId = $event->data->object->metadata->donation_id ?? null;

            $donation = $donationId
                ? Donation::find($donationId)
                : Donation::where('gateway_reference', $sessionId)->first();

            if ($donation && $donation->status !== 'completed') {
                $donation->markCompleted();

                if ($donation->donor_email) {
                    Mail::to($donation->donor_email)->queue(new DonationReceiptMail($donation));
                }
            }
        }

        return response('OK', 200);
    }
}
