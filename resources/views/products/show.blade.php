<x-public-layout :title="$product->name">

    <x-breadcrumbs :items="[
        ['label' => 'Products & Services', 'url' => route('products.index')],
        ['label' => $product->name],
    ]" />

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                {{-- Product image --}}
                <div>
                    <div class="rounded-2xl overflow-hidden bg-purple-100 dark:bg-purple-900 aspect-[4/3]">
                        @if ($product->imageUrl())
                            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-24 h-24 text-purple-300 dark:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Product details --}}
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">
                            {{ \App\Models\Product::CATEGORIES[$product->category] ?? $product->category }}
                        </span>
                        @if ($product->isDigital())
                            <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-2.5 py-0.5 rounded-full">Digital Download</span>
                        @endif
                    </div>

                    <h1 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400">{{ $product->name }}</h1>

                    @if ($product->short_description)
                        <p class="mt-4 text-gray-600 dark:text-purple-200">{{ $product->short_description }}</p>
                    @endif

                    <div class="mt-6 flex items-baseline gap-3">
                        <span class="text-3xl font-bold text-purple-800 dark:text-gold-400">{{ $product->formattedPrice() }}</span>
                        @if ($product->hasDiscount())
                            <span class="text-lg text-gray-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                            <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% off
                            </span>
                        @endif
                    </div>

                    @if (! $product->isDigital() && $product->stock !== null)
                        <p class="mt-2 text-sm {{ $product->inStock() ? 'text-green-600 dark:text-green-400' : 'text-red-500' }}">
                            {{ $product->inStock() ? ($product->stock <= 5 ? "Only {$product->stock} left" : 'In stock') : 'Out of stock' }}
                        </p>
                    @endif

                    <div class="mt-8">
                        @if ($product->inStock())
                            <livewire:add-to-cart :product="$product" />
                        @else
                            <button disabled class="w-full rounded-full bg-gray-300 px-8 py-3.5 text-sm font-bold text-gray-500 cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif
                    </div>

                    @if ($product->isDigital())
                        <div class="mt-4 flex items-center gap-2 text-sm text-gray-500 dark:text-purple-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Instant digital download after payment
                        </div>
                    @endif
                </div>
            </div>

            {{-- Full description --}}
            @if ($product->description)
                <div class="mt-16 pt-12 border-t border-purple-100 dark:border-purple-800">
                    <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 mb-6">Description</h2>
                    <div class="prose dark:prose-invert prose-headings:font-display max-w-none">
                        {!! $product->description !!}
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Related products --}}
    @if ($related->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 text-center mb-8">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($related as $item)
                        <a href="{{ route('products.show', $item->slug) }}" class="bg-white dark:bg-purple-950 rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-md transition-shadow">
                            <div class="h-40 bg-purple-100 dark:bg-purple-900 bg-cover bg-center"
                                @if($item->imageUrl())
                                    style="background-image: url('{{ $item->imageUrl() }}')"
                                @endif
                            ></div>
                            <div class="p-4">
                                <h3 class="font-display font-bold text-purple-800 dark:text-gold-400">{{ $item->name }}</h3>
                                <p class="mt-1 text-lg font-bold text-purple-700 dark:text-gold-300">{{ $item->formattedPrice() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-public-layout>
