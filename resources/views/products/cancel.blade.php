<x-public-layout :title="'Order Cancelled'">

    <section class="bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display font-bold text-3xl text-white">Order Cancelled</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 mb-3">Payment was cancelled</h2>
            <p class="text-gray-600 dark:text-purple-200 mb-8">
                No charges were made. You can try again or browse other products.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('cart') }}" class="inline-flex items-center justify-center rounded-full border-2 border-purple-200 dark:border-purple-700 px-6 py-3 text-sm font-semibold text-purple-700 dark:text-purple-200 hover:bg-purple-50 dark:hover:bg-purple-800 transition-colors">
                    Return to Cart
                </a>
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-full bg-gold-500 px-6 py-3 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                    Browse Products
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
