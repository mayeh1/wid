<x-public-layout :title="'Complete Your Donation'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Almost There</p>
            <h1 class="font-display font-bold text-4xl text-white">Complete Your Donation</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6 mb-8 text-center">
                <p class="text-sm text-gray-500 dark:text-purple-300">Donation Amount</p>
                <p class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400">${{ number_format($donation->amount, 2) }}</p>
                <p class="text-xs text-gray-400 dark:text-purple-400 mt-1">Receipt #{{ $donation->receipt_number }}</p>
            </div>

            <div class="bg-white dark:bg-purple-950 border border-purple-100 dark:border-purple-800 rounded-2xl p-6">
                <h2 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400 mb-3">
                    Pay via {{ $donation->paymentMethod->name }}
                </h2>
                <div class="text-sm text-gray-600 dark:text-purple-200 whitespace-pre-line">
                    {{ $donation->paymentMethod->instructions ?: 'Please contact us for payment instructions.' }}
                </div>
            </div>

            <p class="mt-6 text-sm text-gray-500 dark:text-purple-300 text-center">
                Once we receive and verify your payment, we'll email a receipt to <strong>{{ $donation->donor_email }}</strong>.
            </p>

            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full bg-purple-700 px-8 py-3.5 text-sm font-bold text-white hover:bg-purple-800 transition-colors">
                    Back to Home
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
