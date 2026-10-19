<x-public-layout :title="'Products & Services'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Support Our Mission</p>
            <h1 class="font-display font-bold text-4xl text-white">Products &amp; Services</h1>
            <p class="mt-6 text-purple-200">
                Books, magazines, consultancy guides, toolkits, training courses, and reports
                — every purchase directly supports women's empowerment.
            </p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16" x-data="{ filter: 'all' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Category filter pills --}}
            @if ($categories->count() > 1)
                <div class="flex flex-wrap gap-2 justify-center mb-10">
                    <button @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-purple-700 text-white' : 'bg-purple-100 dark:bg-purple-800 text-purple-700 dark:text-purple-200'"
                        class="px-4 py-1.5 rounded-full text-sm font-semibold transition-colors">
                        All
                    </button>
                    @foreach ($categories as $cat)
                        <button @click="filter = '{{ $cat }}'"
                            :class="filter === '{{ $cat }}' ? 'bg-purple-700 text-white' : 'bg-purple-100 dark:bg-purple-800 text-purple-700 dark:text-purple-200'"
                            class="px-4 py-1.5 rounded-full text-sm font-semibold transition-colors">
                            {{ \App\Models\Product::CATEGORIES[$cat] ?? $cat }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse ($products as $product)
                    <div x-show="filter === 'all' || filter === '{{ $product->category }}'"
                         x-transition
                         class="group rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-lg transition-shadow bg-white dark:bg-purple-900/30 flex flex-col">

                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <div class="h-52 bg-purple-100 dark:bg-purple-900 bg-cover bg-center"
                                @if($product->imageUrl())
                                    style="background-image: url('{{ $product->imageUrl() }}')"
                                @else
                                    style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=7c3aed&color=fff&size=400')"
                                @endif
                            ></div>
                        </a>

                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">
                                    {{ \App\Models\Product::CATEGORIES[$product->category] ?? $product->category }}
                                </span>
                                @if ($product->isDigital())
                                    <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded-full">Digital</span>
                                @endif
                            </div>

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
                                    <span class="text-xl font-bold text-purple-800 dark:text-gold-400">{{ $product->formattedPrice() }}</span>
                                    @if ($product->hasDiscount())
                                        <span class="text-sm text-gray-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                                    @endif
                                </div>
                                @if (! $product->inStock())
                                    <span class="text-xs text-red-500 font-semibold">Out of stock</span>
                                @endif
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}"
                               class="mt-4 inline-flex items-center justify-center w-full rounded-full bg-gold-500 px-5 py-2.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 dark:text-purple-300 py-12">
                        Products are being added — check back soon.
                    </p>
                @endforelse
            </div>
        </div>
    </section>

</x-public-layout>
