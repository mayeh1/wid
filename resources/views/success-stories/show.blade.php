<x-public-layout :title="$story->title" :description="$story->excerpt">

    <section class="relative bg-purple-950 py-20">
        @if ($story->featuredImageUrl())
            <div class="absolute inset-0 opacity-30 bg-cover bg-center" style="background-image: url('{{ $story->featuredImageUrl() }}')"></div>
        @endif
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if ($story->category)
                <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-400">{{ $story->category }}</span>
            @endif
            <h1 class="mt-3 font-display font-bold text-4xl text-white">{{ $story->title }}</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($story->video_url)
                <div class="aspect-video mb-8 rounded-2xl overflow-hidden">
                    <iframe src="{{ $story->video_url }}" class="w-full h-full" allowfullscreen></iframe>
                </div>
            @endif

            <div class="prose dark:prose-invert prose-headings:font-display max-w-none">
                {!! $story->story !!}
            </div>

            @php $galleryUrls = $story->getMedia('gallery'); @endphp
            @if ($galleryUrls->isNotEmpty())
                <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach ($galleryUrls as $media)
                        <img src="{{ $media->getUrl() }}" alt="{{ $story->title }}" class="rounded-xl object-cover w-full h-40">
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-6">More Stories</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach ($related as $item)
                        <a href="{{ route('success-stories.show', $item->slug) }}" class="bg-white dark:bg-purple-950 rounded-2xl p-5 border border-purple-100 dark:border-purple-800 hover:shadow-md transition-shadow">
                            <h3 class="font-semibold text-purple-800 dark:text-gold-400">{{ $item->title }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-public-layout>
