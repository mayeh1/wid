<x-public-layout :title="$project->title">

    <section class="relative bg-purple-950 py-20">
        @if ($project->featuredImageUrl())
            <div class="absolute inset-0 opacity-30 bg-cover bg-center" style="background-image: url('{{ $project->featuredImageUrl() }}')"></div>
        @endif
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-400">{{ ucfirst($project->status) }}</span>
            <h1 class="mt-3 font-display font-bold text-4xl text-white">{{ $project->title }}</h1>
            @if ($project->location)
                <p class="mt-3 text-purple-300 text-sm">{{ $project->location }}</p>
            @endif
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($project->budget)
                <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6 mb-10">
                    <div class="flex justify-between text-sm font-semibold text-purple-800 dark:text-gold-400 mb-2">
                        <span>${{ number_format($project->raised, 0) }} raised</span>
                        <span>Goal: ${{ number_format($project->budget, 0) }}</span>
                    </div>
                    <div class="h-3 rounded-full bg-purple-100 dark:bg-purple-800 overflow-hidden">
                        <div class="h-full bg-gold-500" style="width: {{ min(100, $project->progress_percent) }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-purple-300">{{ $project->progress_percent }}% funded</p>
                </div>
            @endif

            @if ($project->description)
                <div class="prose dark:prose-invert prose-headings:font-display max-w-none">
                    {!! $project->description !!}
                </div>
            @endif

            @if (!empty($project->timeline))
                <div class="mt-10">
                    <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 mb-6">Project Timeline</h2>
                    <ol class="border-l-2 border-purple-200 dark:border-purple-800 pl-6 space-y-6">
                        @foreach ($project->timeline as $item)
                            <li>
                                <p class="text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ $item['date'] ?? '' }}</p>
                                <p class="text-sm text-gray-700 dark:text-purple-200">{{ $item['label'] ?? '' }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

            <div class="mt-10 text-center">
                <a href="{{ route('donate.project', $project->slug) }}" class="inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                    Donate to This Project
                </a>
            </div>
        </div>
    </section>

    @php $galleryUrls = $project->getMedia('gallery'); @endphp
    @if ($galleryUrls->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 text-center mb-8">Gallery</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach ($galleryUrls as $media)
                        <img src="{{ $media->getUrl() }}" alt="{{ $project->title }}" class="rounded-xl object-cover w-full h-48">
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-public-layout>
