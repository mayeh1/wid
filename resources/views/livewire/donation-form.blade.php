<div class="bg-white dark:bg-purple-900/30 rounded-2xl border border-purple-100 dark:border-purple-800 p-6 sm:p-8">

    {{-- Frequency --}}
    <div class="flex rounded-full bg-purple-50 dark:bg-purple-950 p-1 mb-6">
        @foreach (['one_time' => 'One-Time', 'monthly' => 'Monthly', 'annual' => 'Annual'] as $value => $label)
            <button type="button" wire:click="$set('frequency', '{{ $value }}')"
                    class="flex-1 rounded-full py-2 text-sm font-semibold transition-colors {{ $frequency === $value ? 'bg-purple-700 text-white' : 'text-purple-700 dark:text-purple-200' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Amount --}}
    <p class="text-sm font-semibold text-gray-700 dark:text-purple-200 mb-3">Choose an amount</p>
    <div class="grid grid-cols-3 gap-3 mb-4">
        @foreach ($presetAmounts as $preset)
            <button type="button" wire:click="selectAmount('{{ $preset }}')"
                    class="rounded-xl py-3 text-sm font-bold border-2 transition-colors {{ $amount === (string) $preset && !$customAmount ? 'border-gold-500 bg-gold-50 dark:bg-gold-500/10 text-purple-800 dark:text-gold-400' : 'border-purple-100 dark:border-purple-800 text-gray-600 dark:text-purple-200' }}">
                ${{ $preset }}
            </button>
        @endforeach
    </div>
    <div class="relative mb-6">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">$</span>
        <input type="number" wire:model.live="customAmount" placeholder="Custom amount" min="1"
               class="w-full rounded-xl border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white pl-8 text-sm">
    </div>
    @error('customAmount')<p class="text-xs text-red-600 mb-4">{{ $message }}</p>@enderror

    {{-- Donor info --}}
    <div class="space-y-4">
        <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-purple-200">
            <input type="checkbox" wire:model.live="isAnonymous" class="rounded border-gray-300 text-purple-700">
            Give anonymously
        </label>

        @unless ($isAnonymous)
            <div>
                <input type="text" wire:model="donorName" placeholder="Full name"
                       class="w-full rounded-xl border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
                @error('donorName')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        @endunless

        <div>
            <input type="email" wire:model="donorEmail" placeholder="Email address"
                   class="w-full rounded-xl border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
            @error('donorEmail')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Payment method --}}
    <p class="text-sm font-semibold text-gray-700 dark:text-purple-200 mt-6 mb-3">Payment method</p>
    <div class="space-y-2">
        @forelse ($this->paymentMethods as $method)
            <label class="flex items-center gap-3 rounded-xl border-2 px-4 py-3 cursor-pointer transition-colors {{ $paymentMethodId === $method->id ? 'border-gold-500 bg-gold-50 dark:bg-gold-500/10' : 'border-purple-100 dark:border-purple-800' }}">
                <input type="radio" wire:model.live="paymentMethodId" value="{{ $method->id }}" class="text-purple-700">
                @if ($method->logoUrl())
                    <img src="{{ $method->logoUrl() }}" alt="{{ $method->name }}" class="h-6 w-auto">
                @endif
                <span class="text-sm font-semibold text-gray-700 dark:text-purple-200">{{ $method->name }}</span>
            </label>
        @empty
            <p class="text-sm text-gray-500 dark:text-purple-300">No payment methods are currently available. Please check back soon.</p>
        @endforelse
    </div>
    @error('paymentMethodId')<p class="text-xs text-red-600 mt-2">{{ $message }}</p>@enderror

    <button type="button" wire:click="donate" wire:loading.attr="disabled"
            class="mt-8 w-full rounded-full bg-gold-500 px-8 py-4 text-sm font-bold text-purple-950 hover:bg-gold-400 disabled:opacity-60 transition-colors">
        <span wire:loading.remove>Donate ${{ number_format($this->finalAmount, 2) }} {{ $frequency !== 'one_time' ? '/ '.($frequency === 'monthly' ? 'month' : 'year') : '' }}</span>
        <span wire:loading>Processing...</span>
    </button>

    <p class="mt-4 text-center text-xs text-gray-400 dark:text-purple-400">
        Women in Development, Inc. is a 501(c)(3) nonprofit. Your donation may be tax-deductible.
    </p>
</div>
