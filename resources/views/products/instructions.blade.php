<x-public-layout :title="'Payment Instructions'">

    <section class="bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display font-bold text-3xl text-white">Payment Instructions</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-purple-900/30 rounded-2xl border border-purple-100 dark:border-purple-800 p-6 sm:p-8">

                <div class="text-center mb-8">
                    <p class="text-sm text-gray-500 dark:text-purple-300">Order Number</p>
                    <p class="text-lg font-bold text-purple-800 dark:text-gold-400">{{ $order->order_number }}</p>
                    <p class="mt-2 text-2xl font-bold text-purple-800 dark:text-gold-400">${{ number_format($order->total, 2) }}</p>
                </div>

                @if ($order->paymentMethod)
                    <div class="bg-purple-50 dark:bg-purple-950 rounded-xl p-6">
                        <h2 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400 mb-3">
                            {{ $order->paymentMethod->name }}
                        </h2>
                        @if ($order->paymentMethod->instructions)
                            <div class="prose dark:prose-invert prose-sm max-w-none">
                                {!! nl2br(e($order->paymentMethod->instructions)) !!}
                            </div>
                        @endif
                    </div>
                @endif

                <h3 class="font-display font-bold text-md text-purple-800 dark:text-gold-400 mt-6 mb-3">Items Ordered</h3>
                <div class="divide-y divide-purple-100 dark:divide-purple-800">
                    @foreach ($order->items as $item)
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-700 dark:text-purple-200">{{ $item->product_name }} &times; {{ $item->quantity }}</span>
                            <span class="text-sm font-semibold text-purple-800 dark:text-gold-400">${{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <p class="mt-6 text-sm text-gray-500 dark:text-purple-300 text-center">
                    Please include your order number <strong>{{ $order->order_number }}</strong> as a reference. Your order will be fulfilled once payment is confirmed.
                </p>
            </div>
        </div>
    </section>

</x-public-layout>
