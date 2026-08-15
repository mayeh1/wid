<x-public-layout :title="'Gallery'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Moments</p>
            <h1 class="font-display font-bold text-4xl text-white">Gallery</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($albums as $album)
                <a href="{{ route('gallery.show', $album->slug) }}" class="group relative rounded-2xl overflow-hidden aspect-square bg-purple-100 dark:bg-purple-900">
                    @if ($album->coverUrl())
                        <img src="{{ $album->coverUrl() }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end p-4">
                        <p class="text-white font-semibold text-sm">{{ $album->title }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500 dark:text-purple-300">No albums yet.</p>
            @endforelse
        </div>
    </section>

</x-public-layout>
