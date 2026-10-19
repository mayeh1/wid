<div class="flex items-center gap-3">
    <div class="flex items-center rounded-xl border border-purple-200 dark:border-purple-700">
        <button type="button" wire:click="$set('quantity', Math.max(1, {{ $quantity }} - 1))"
                class="px-3 py-2 text-purple-700 dark:text-purple-200 hover:bg-purple-50 dark:hover:bg-purple-800 rounded-l-xl transition-colors">
            &minus;
        </button>
        <span class="px-4 py-2 text-sm font-semibold text-purple-800 dark:text-gold-400 min-w-[3rem] text-center">{{ $quantity }}</span>
        <button type="button" wire:click="$set('quantity', {{ $quantity }} + 1)"
                class="px-3 py-2 text-purple-700 dark:text-purple-200 hover:bg-purple-50 dark:hover:bg-purple-800 rounded-r-xl transition-colors">
            +
        </button>
    </div>
    <button type="button" wire:click="addToCart" wire:loading.attr="disabled"
            class="flex-1 rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 disabled:opacity-60 transition-colors">
        <span wire:loading.remove>Add to Cart</span>
        <span wire:loading>Adding...</span>
    </button>
</div>
