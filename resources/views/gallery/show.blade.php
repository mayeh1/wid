<x-public-layout :title="$album->title">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display font-bold text-4xl text-white">{{ $album->title }}</h1>
            @if ($album->description)
                <p class="mt-4 text-purple-200">{{ $album->description }}</p>
            @endif
        </div>
    </section>

    @php $photos = $album->getMedia('photos'); @endphp

    <section class="bg-white dark:bg-purple-950 py-16" x-data="{ open: false, active: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse ($photos as $photo)
                <button type="button" @click="open = true; active = '{{ $photo->getUrl() }}'" class="rounded-xl overflow-hidden aspect-square">
                    <img src="{{ $photo->getUrl() }}" alt="{{ $album->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform">
                </button>
            @empty
                <p class="col-span-full text-center text-gray-500 dark:text-purple-300">No photos in this album yet.</p>
            @endforelse
        </div>

        {{-- Lightbox --}}
        <div x-show="open" x-cloak x-transition.opacity @keydown.escape.window="open = false"
             class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-6" @click="open = false">
            <img :src="active" class="max-h-[85vh] max-w-full rounded-lg" @click.stop>
            <button @click="open = false" class="absolute top-6 right-6 text-white text-3xl leading-none">&times;</button>
        </div>
    </section>

</x-public-layout>
