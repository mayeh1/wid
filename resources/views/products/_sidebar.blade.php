<div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl border border-purple-100 dark:border-purple-800 p-5">

    {{-- Type filters --}}
    <h3 class="text-xs font-bold uppercase tracking-wide text-purple-500 dark:text-purple-400 mb-3">Type</h3>
    <div class="space-y-1 mb-6">
        <button @click="typeFilter = 'all'; filter = 'all'"
            :class="typeFilter === 'all' ? 'bg-purple-700 text-white' : 'text-gray-700 dark:text-purple-200 hover:bg-purple-100 dark:hover:bg-purple-800'"
            class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
            All Items
        </button>
        <button @click="typeFilter = (typeFilter === 'digital' || typeFilter === 'physical') ? typeFilter : 'all'; typeFilter = 'all'"
            x-show="false"></button>
        @foreach (['digital' => 'Digital Products', 'physical' => 'Physical Products', 'service' => 'IT & Software Services'] as $type => $label)
            <button @click="typeFilter = typeFilter === '{{ $type }}' ? 'all' : '{{ $type }}'; filter = 'all'"
                :class="typeFilter === '{{ $type }}' ? 'bg-purple-700 text-white' : 'text-gray-700 dark:text-purple-200 hover:bg-purple-100 dark:hover:bg-purple-800'"
                class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                @if ($type === 'service')
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                @elseif ($type === 'digital')
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                @else
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                @endif
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Category filters grouped --}}
    @foreach ($sidebarGroups as $groupName => $cats)
        <h3 class="text-xs font-bold uppercase tracking-wide text-purple-500 dark:text-purple-400 mb-3 {{ !$loop->first ? 'mt-5' : '' }}">{{ $groupName }}</h3>
        <div class="space-y-0.5">
            @foreach ($cats as $cat)
                <button @click="filter = filter === '{{ $cat }}' ? 'all' : '{{ $cat }}'; typeFilter = 'all'"
                    :class="filter === '{{ $cat }}' ? 'bg-gold-500 text-purple-950 font-bold' : 'text-gray-600 dark:text-purple-200 hover:bg-purple-100 dark:hover:bg-purple-800'"
                    class="w-full text-left px-3 py-1.5 rounded-lg text-sm transition-colors"
                    data-cat="{{ $cat }}">
                    {{ \App\Models\Product::CATEGORIES[$cat] ?? $cat }}
                </button>
            @endforeach
        </div>
    @endforeach
</div>
