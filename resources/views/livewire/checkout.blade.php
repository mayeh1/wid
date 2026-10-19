<div class="bg-white dark:bg-purple-900/30 rounded-2xl border border-purple-100 dark:border-purple-800 p-6 sm:p-8">

    {{-- Order summary --}}
    <h2 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400 mb-4">Order Summary</h2>
    <div class="divide-y divide-purple-100 dark:divide-purple-800 mb-6">
        @foreach ($this->cart as $item)
            <div class="flex items-center justify-between py-3">
                <div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-purple-200">{{ $item['name'] }}</span>
                    <span class="text-xs text-gray-400 dark:text-purple-400"> &times; {{ $item['quantity'] }}</span>
                </div>
                <span class="text-sm font-bold text-purple-800 dark:text-gold-400">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
            </div>
        @endforeach
        <div class="flex items-center justify-between py-3">
            <span class="text-base font-bold text-purple-800 dark:text-gold-400">Total</span>
            <span class="text-xl font-bold text-purple-800 dark:text-gold-400">${{ number_format($this->total, 2) }}</span>
        </div>
    </div>

    {{-- Customer info --}}
    <h2 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400 mb-4">Your Details</h2>
    <div class="space-y-4 mb-6">
        <div>
            <input type="text" wire:model="customerName" placeholder="Full name"
                   class="w-full rounded-xl border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
            @error('customerName')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <input type="email" wire:model="customerEmail" placeholder="Email address"
                   class="w-full rounded-xl border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
            @error('customerEmail')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Payment method --}}
    <h2 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400 mb-4">Payment Method</h2>
    <div class="space-y-2 mb-6">
        @forelse ($this->paymentMethods as $method)
            <label class="flex items-center gap-3 rounded-xl border-2 px-4 py-3 cursor-pointer transition-colors {{ $paymentMethodId === $method->id ? 'border-gold-500 bg-gold-50 dark:bg-gold-500/10' : 'border-purple-100 dark:border-purple-800' }}">
                <input type="radio" wire:model.live="paymentMethodId" value="{{ $method->id }}" class="text-purple-700">
                @if ($method->logoUrl())
                    <img src="{{ $method->logoUrl() }}" alt="{{ $method->name }}" class="h-6 w-auto">
                @endif
                <span class="text-sm font-semibold text-gray-700 dark:text-purple-200">{{ $method->name }}</span>
            </label>
        @empty
            <p class="text-sm text-gray-500 dark:text-purple-300">No payment methods are currently available.</p>
        @endforelse
    </div>
    @error('paymentMethodId')<p class="text-xs text-red-600 mb-4">{{ $message }}</p>@enderror

    <button type="button" wire:click="placeOrder" wire:loading.attr="disabled"
            class="w-full rounded-full bg-gold-500 px-8 py-4 text-sm font-bold text-purple-950 hover:bg-gold-400 disabled:opacity-60 transition-colors">
        <span wire:loading.remove>Pay ${{ number_format($this->total, 2) }}</span>
        <span wire:loading>Processing...</span>
    </button>

    <p class="mt-4 text-center text-xs text-gray-400 dark:text-purple-400">
        All proceeds support Women in Development, Inc. programs.
    </p>
</div>
