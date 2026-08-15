<x-public-layout :title="$post->title" :description="$post->excerpt">

    <section class="bg-purple-950 py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-400">{{ str_replace('_', ' ', $post->type) }}</span>
            <h1 class="mt-3 font-display font-bold text-4xl text-white">{{ $post->title }}</h1>
            <p class="mt-4 text-sm text-purple-300">{{ $post->published_at?->format('F j, Y') }}</p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($post->featuredImageUrl())
                <img src="{{ $post->featuredImageUrl() }}" alt="{{ $post->title }}" class="w-full rounded-2xl mb-8">
            @endif

            @if ($post->video_url)
                <div class="aspect-video mb-8 rounded-2xl overflow-hidden">
                    <iframe src="{{ $post->video_url }}" class="w-full h-full" allowfullscreen></iframe>
                </div>
            @endif

            <div class="prose dark:prose-invert prose-headings:font-display max-w-none">
                {!! $post->body !!}
            </div>

            @php $galleryUrls = $post->getMedia('gallery'); @endphp
            @if ($galleryUrls->isNotEmpty())
                <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach ($galleryUrls as $media)
                        <img src="{{ $media->getUrl() }}" alt="{{ $post->title }}" class="rounded-xl object-cover w-full h-40">
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</x-public-layout>
