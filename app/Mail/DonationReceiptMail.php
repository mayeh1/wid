<?php

namespace App\Mail;

use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Donation Receipt — Women in Development, Inc.',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-receipt',
            with: ['donation' => $this->donation],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('donate.receipt-pdf', ['donation' => $this->donation->load('paymentMethod', 'campaign', 'project')]);

        return [
            Attachment::fromData(fn () => $pdf->output(), "WID-Receipt-{$this->donation->receipt_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
