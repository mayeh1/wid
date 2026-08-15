<x-public-layout :title="'Success Stories'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Real Impact</p>
            <h1 class="font-display font-bold text-4xl text-white">Success Stories</h1>
            <p class="mt-6 text-purple-200">Meet the women whose lives have been transformed through WID programs.</p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($stories as $story)
                <a href="{{ route('success-stories.show', $story->slug) }}" class="group rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-lg transition-shadow">
                    <div class="h-44 bg-purple-100 dark:bg-purple-900 @if($story->featuredImageUrl()) bg-cover bg-center @endif" @if($story->featuredImageUrl()) style="background-image: url('{{ $story->featuredImageUrl() }}')" @endif></div>
                    <div class="p-6 bg-white dark:bg-purple-900/30">
                        @if ($story->category)
                            <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ $story->category }}</span>
                        @endif
                        <h2 class="mt-2 font-display font-bold text-lg text-purple-800 dark:text-gold-400 group-hover:underline">{{ $story->title }}</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-purple-200 line-clamp-3">{{ $story->excerpt }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500 dark:text-purple-300">Stories are being added — check back soon.</p>
            @endforelse
        </div>
    </section>

</x-public-layout>
