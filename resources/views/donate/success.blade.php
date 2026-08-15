<x-public-layout :title="'Thank You'">

    <section class="bg-purple-950 py-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-16 h-16 rounded-full bg-gold-500 flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-purple-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
            </div>
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Thank You</p>
            <h1 class="font-display font-bold text-4xl text-white">
                @if ($donation->status === 'completed')
                    Your gift of ${{ number_format($donation->amount, 2) }} means the world.
                @else
                    We're finalizing your donation
                @endif
            </h1>
            <p class="mt-6 text-purple-200">
                @if ($donation->status === 'completed')
                    A receipt has been sent to {{ $donation->donor_email }}. Thank you for empowering women and girls.
                @else
                    We're still confirming your payment with our provider — you'll receive a receipt by email as soon as it's confirmed.
                @endif
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                @if ($donation->status === 'completed')
                    <a href="{{ route('donations.receipt', $donation->receipt_number) }}" class="inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                        Download Receipt
                    </a>
                @endif
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full border-2 border-purple-300 px-8 py-3.5 text-sm font-bold text-white hover:bg-white hover:text-purple-950 transition-colors">
                    Back to Home
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
