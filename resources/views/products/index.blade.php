<x-public-layout :title="'Products & Services'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Support Our Mission</p>
            <h1 class="font-display font-bold text-4xl text-white">Products &amp; Services</h1>
            <p class="mt-6 text-purple-200">
                Publications, IT solutions, software development, and consultancy
                — every purchase directly supports women's empowerment.
            </p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16" x-data="{ filter: 'all', typeFilter: 'all', mobileSidebar: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mobile sidebar toggle --}}
            <div class="lg:hidden mb-6">
                <button @click="mobileSidebar = !mobileSidebar"
                    class="w-full flex items-center justify-between bg-purple-50 dark:bg-purple-900/30 rounded-xl px-4 py-3 text-sm font-semibold text-purple-800 dark:text-gold-400 border border-purple-100 dark:border-purple-800">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 010 2H4a1 1 0 01-1-1zm4 6a1 1 0 011-1h8a1 1 0 010 2H8a1 1 0 01-1-1zm2 6a1 1 0 011-1h4a1 1 0 010 2h-4a1 1 0 01-1-1z"/></svg>
                        Filter by Category
                    </span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileSidebar }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="mobileSidebar" x-cloak x-collapse class="mt-3">
                    @include('products._sidebar')
                </div>
            </div>

            <div class="flex gap-8">
                {{-- Desktop sidebar --}}
                <aside class="hidden lg:block w-64 flex-shrink-0">
                    <div class="sticky top-24">
                        @include('products._sidebar')
                    </div>
                </aside>

                {{-- Product grid --}}
                <div class="flex-1 min-w-0">
                    {{-- Active filter indicator --}}
                    <div x-show="filter !== 'all' || typeFilter !== 'all'" x-cloak class="mb-6 flex items-center gap-2 flex-wrap">
                        <span class="text-sm text-gray-500 dark:text-purple-300">Showing:</span>
                        <template x-if="typeFilter !== 'all'">
                            <span class="inline-flex items-center gap-1 bg-purple-100 dark:bg-purple-800 text-purple-700 dark:text-purple-200 text-xs font-semibold px-3 py-1 rounded-full">
                                <span x-text="typeFilter === 'service' ? 'Services' : (typeFilter === 'digital' ? 'Digital' : 'Physical')"></span>
                                <button @click="typeFilter = 'all'" class="ml-1 hover:text-red-500">&times;</button>
                            </span>
                        </template>
                        <template x-if="filter !== 'all'">
                            <span class="inline-flex items-center gap-1 bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400 text-xs font-semibold px-3 py-1 rounded-full">
                                <span x-text="document.querySelector('[data-cat=\'' + filter + '\']')?.textContent?.trim() || filter"></span>
                                <button @click="filter = 'all'" class="ml-1 hover:text-red-500">&times;</button>
                            </span>
                        </template>
                        <button @click="filter = 'all'; typeFilter = 'all'" class="text-xs text-purple-500 hover:text-purple-700 dark:hover:text-gold-400 underline">Clear all</button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse ($products as $product)
                            <div x-show="(filter === 'all' || filter === '{{ $product->category }}') && (typeFilter === 'all' || typeFilter === '{{ $product->type }}')"
                                 x-transition
                                 class="group rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-lg transition-shadow bg-white dark:bg-purple-900/30 flex flex-col">

                                <a href="{{ route('products.show', $product->slug) }}" class="block">
                                    <div class="h-48 bg-purple-100 dark:bg-purple-900 bg-cover bg-center relative"
                                        @if($product->imageUrl())
                                            style="background-image: url('{{ $product->imageUrl() }}')"
                                        @else
                                            style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=7c3aed&color=fff&size=400')"
                                        @endif
                                    >
                                        @if ($product->isService())
                                            <span class="absolute top-3 right-3 text-xs bg-green-600 text-white px-2.5 py-1 rounded-full font-semibold">Service</span>
                                        @elseif ($product->isDigital())
                                            <span class="absolute top-3 right-3 text-xs bg-blue-600 text-white px-2.5 py-1 rounded-full font-semibold">Digital</span>
                                        @endif
                                        @if ($product->hasDiscount())
                                            <span class="absolute top-3 left-3 text-xs bg-red-500 text-white px-2.5 py-1 rounded-full font-semibold">Sale</span>
                                        @endif
                                    </div>
                                </a>

                                <div class="p-5 flex flex-col flex-1">
                                    <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400 mb-1">
                                        {{ \App\Models\Product::CATEGORIES[$product->category] ?? $product->category }}
                                    </span>

                                    <a href="{{ route('products.show', $product->slug) }}" class="block flex-1">
                                        <h2 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400 group-hover:underline">
                                            {{ $product->name }}
                                        </h2>
                                        @if ($product->short_description)
                                            <p class="mt-1 text-sm text-gray-600 dark:text-purple-200 line-clamp-2">{{ $product->short_description }}</p>
                                        @endif
                                    </a>

                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-baseline gap-2">
                                            @if ($product->isService() && $product->price <= 0)
                                                <span class="text-lg font-bold text-purple-800 dark:text-gold-400">Get a Quote</span>
                                            @else
                                                <span class="text-xl font-bold text-purple-800 dark:text-gold-400">{{ $product->formattedPrice() }}</span>
                                                @if ($product->hasDiscount())
                                                    <span class="text-sm text-gray-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                                                @endif
                                            @endif
                                        </div>
                                        @if (! $product->inStock())
                                            <span class="text-xs text-red-500 font-semibold">Out of stock</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="mt-4 inline-flex items-center justify-center w-full rounded-full bg-gold-500 px-5 py-2.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                                        {{ $product->isService() ? 'Learn More' : 'View Details' }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-gray-500 dark:text-purple-300 py-12">
                                Products and services are being added — check back soon.
                            </p>
                        @endforelse
                    </div>

                    {{-- No results message (shown via Alpine when filters hide everything) --}}
                    <div x-show="document.querySelectorAll('[x-show]:not([style*=\'display: none\'])').length === 0"
                         x-cloak class="text-center py-12">
                        <p class="text-gray-500 dark:text-purple-300">No items match your filter. Try a different category.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-public-layout>
