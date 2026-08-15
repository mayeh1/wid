<x-public-layout :title="$program->title">

    <section class="relative bg-purple-950 py-20">
        @if ($program->featuredImageUrl())
            <div class="absolute inset-0 opacity-30 bg-cover bg-center" style="background-image: url('{{ $program->featuredImageUrl() }}')"></div>
        @endif
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-400">{{ \App\Models\Program::CATEGORIES[$program->category] ?? $program->category }}</span>
            <h1 class="mt-3 font-display font-bold text-4xl text-white">{{ $program->title }}</h1>
            @if ($program->excerpt)
                <p class="mt-5 text-purple-200">{{ $program->excerpt }}</p>
            @endif
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($program->description)
                <div class="prose dark:prose-invert prose-headings:font-display max-w-none">
                    {!! $program->description !!}
                </div>
            @endif

            @if ($program->apply_url)
                <div class="mt-10 text-center">
                    <a href="{{ $program->apply_url }}" class="inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                        Apply Now
                    </a>
                </div>
            @endif
        </div>
    </section>

    @php $galleryUrls = $program->getMedia('gallery'); @endphp
    @if ($galleryUrls->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 text-center mb-8">Gallery</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach ($galleryUrls as $media)
                        <img src="{{ $media->getUrl() }}" alt="{{ $program->title }}" class="rounded-xl object-cover w-full h-48">
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($program->success_stories)
        <section class="bg-white dark:bg-purple-950 py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 text-center mb-8">Success Stories</h2>
                <div class="prose dark:prose-invert max-w-none">
                    {!! $program->success_stories !!}
                </div>
            </div>
        </section>
    @endif

    @if ($related->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 text-center mb-8">Related Programs</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach ($related as $item)
                        <a href="{{ route('programs.show', $item->slug) }}" class="bg-white dark:bg-purple-950 rounded-2xl p-6 border border-purple-100 dark:border-purple-800 hover:shadow-md transition-shadow">
                            <h3 class="font-display font-bold text-purple-800 dark:text-gold-400">{{ $item->title }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-purple-200 line-clamp-2">{{ $item->excerpt }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-public-layout>
