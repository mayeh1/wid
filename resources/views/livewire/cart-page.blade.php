<div>
    @if (empty($cart))
        <div class="text-center py-16">
            <svg class="mx-auto w-16 h-16 text-purple-300 dark:text-purple-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
            <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-2">Your cart is empty</h2>
            <p class="text-gray-600 dark:text-purple-200 mb-6">Browse our products and add something to your cart.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center rounded-full bg-gold-500 px-6 py-3 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                Browse Products
            </a>
        </div>
    @else
        <div class="space-y-4 mb-8">
            @foreach ($cart as $id => $item)
                <div class="flex items-center gap-4 bg-white dark:bg-purple-900/30 rounded-2xl border border-purple-100 dark:border-purple-800 p-4">
                    {{-- Image --}}
                    <div class="w-20 h-20 rounded-xl bg-purple-100 dark:bg-purple-900 bg-cover bg-center flex-shrink-0"
                        @if ($item['image'])
                            style="background-image: url('{{ $item['image'] }}')"
                        @endif
                    ></div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-purple-800 dark:text-gold-400 truncate">{{ $item['name'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-purple-200">${{ number_format($item['price'], 2) }} each</p>
                    </div>

                    {{-- Quantity --}}
                    <div class="flex items-center rounded-lg border border-purple-200 dark:border-purple-700">
                        <button type="button" wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] - 1 }})"
                                class="px-2.5 py-1.5 text-purple-700 dark:text-purple-200 hover:bg-purple-50 dark:hover:bg-purple-800 rounded-l-lg text-sm">
                            &minus;
                        </button>
                        <span class="px-3 py-1.5 text-sm font-semibold text-purple-800 dark:text-gold-400">{{ $item['quantity'] }}</span>
                        <button type="button" wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] + 1 }})"
                                class="px-2.5 py-1.5 text-purple-700 dark:text-purple-200 hover:bg-purple-50 dark:hover:bg-purple-800 rounded-r-lg text-sm">
                            +
                        </button>
                    </div>

                    {{-- Subtotal --}}
                    <div class="text-right w-24 flex-shrink-0">
                        <p class="font-bold text-purple-800 dark:text-gold-400">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                    </div>

                    {{-- Remove --}}
                    <button type="button" wire:click="removeItem({{ $id }})" class="text-gray-400 hover:text-red-500 transition-colors flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>

        {{-- Summary --}}
        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <span class="text-lg font-semibold text-purple-800 dark:text-gold-400">Total</span>
                <span class="text-2xl font-bold text-purple-800 dark:text-gold-400">${{ number_format($this->subtotal, 2) }}</span>
            </div>

            <div class="flex gap-3">
                <button type="button" wire:click="clearCart"
                        class="rounded-full border-2 border-purple-200 dark:border-purple-700 px-6 py-3 text-sm font-semibold text-purple-700 dark:text-purple-200 hover:bg-purple-50 dark:hover:bg-purple-800 transition-colors">
                    Clear Cart
                </button>
                <a href="{{ route('checkout') }}"
                   class="flex-1 inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                    Proceed to Checkout
                </a>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('products.index') }}" class="text-sm text-purple-700 dark:text-purple-300 hover:underline">
                &larr; Continue Shopping
            </a>
        </div>
    @endif
</div>
