<x-public-layout :title="'Order Complete'">

    <section class="bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display font-bold text-3xl text-white">Order {{ $order->status === 'completed' ? 'Complete' : 'Received' }}</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            @if ($order->status === 'completed')
                <div class="w-20 h-20 mx-auto bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 mb-3">Thank you for your purchase!</h2>
                <p class="text-gray-600 dark:text-purple-200 mb-2">
                    Order <strong>{{ $order->order_number }}</strong> has been confirmed.
                </p>
                <p class="text-sm text-gray-500 dark:text-purple-300 mb-8">
                    A confirmation has been sent to <strong>{{ $order->customer_email }}</strong>.
                </p>
            @else
                <div class="w-20 h-20 mx-auto bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 mb-3">Order Received</h2>
                <p class="text-gray-600 dark:text-purple-200 mb-8">
                    Order <strong>{{ $order->order_number }}</strong> is being processed. We'll confirm once payment is verified.
                </p>
            @endif

            {{-- Digital downloads --}}
            @if ($order->status === 'completed')
                @php
                    $digitalItems = $order->items->filter(fn ($item) => $item->product?->isDigital() && $item->product?->downloadUrl());
                @endphp
                @if ($digitalItems->isNotEmpty())
                    <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6 mb-8 text-left">
                        <h3 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400 mb-4">Your Downloads</h3>
                        <div class="space-y-3">
                            @foreach ($digitalItems as $item)
                                <a href="{{ route('orders.download', [$order->order_number, $item->id]) }}"
                                   class="flex items-center gap-3 bg-white dark:bg-purple-950 rounded-xl border border-purple-100 dark:border-purple-800 px-4 py-3 hover:shadow-md transition-shadow">
                                    <svg class="w-6 h-6 text-gold-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-purple-800 dark:text-gold-400">{{ $item->product_name }}</span>
                                    <span class="ml-auto text-xs text-purple-500 dark:text-purple-300">Download</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <a href="{{ route('products.index') }}" class="inline-flex items-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                Continue Shopping
            </a>
        </div>
    </section>

</x-public-layout>
