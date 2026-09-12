<?php

namespace App\Http\Controllers;

use App\Mail\DonationReceiptMail;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Project;
use App\Payments\PaymentGatewayManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DonationController extends Controller
{
    public function donate(Request $request)
    {
        $campaign = $request->route('campaign')
            ? Campaign::published()->where('slug', $request->route('campaign'))->firstOrFail()
            : null;

        $project = $request->route('project')
            ? Project::published()->where('slug', $request->route('project'))->firstOrFail()
            : null;

        return view('donate.index', compact('campaign', 'project'));
    }

    public function instructions(string $receiptNumber)
    {
        $donation = Donation::where('receipt_number', $receiptNumber)->firstOrFail();

        return view('donate.instructions', ['donation' => $donation->load('paymentMethod')]);
    }

    public function success(Request $request, string $receiptNumber)
    {
        $donation = Donation::where('receipt_number', $receiptNumber)->firstOrFail();

        if ($donation->status === 'pending' && $donation->paymentMethod?->isGateway()) {
            $manager = app(PaymentGatewayManager::class);

            try {
                if ($manager->driverFor($donation->paymentMethod)->verify($donation, $donation->paymentMethod)) {
                    $this->sendReceipt($donation);
                }
            } catch (\Throwable $e) {
                report($e);
                // Verification failed here; the webhook (where configured)
                // remains the source of truth and will complete the donation.
            }
        }

        return view('donate.success', ['donation' => $donation->fresh()]);
    }

    public function cancel(string $receiptNumber)
    {
        $donation = Donation::where('receipt_number', $receiptNumber)->firstOrFail();

        return view('donate.cancel', ['donation' => $donation]);
    }

    public function receipt(string $receiptNumber)
    {
        $donation = Donation::where('receipt_number', $receiptNumber)
            ->where('status', 'completed')
            ->firstOrFail();

        $pdf = Pdf::loadView('donate.receipt-pdf', ['donation' => $donation->load('paymentMethod', 'campaign', 'project')]);

        return $pdf->download("WID-Receipt-{$donation->receipt_number}.pdf");
    }

    private function sendReceipt(Donation $donation): void
    {
        if ($donation->donor_email) {
            Mail::to($donation->donor_email)->queue(new DonationReceiptMail($donation));
        }
    }
}
