<x-public-layout :title="'Projects'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Our Work</p>
            <h1 class="font-display font-bold text-4xl text-white">Projects</h1>
            <p class="mt-6 text-purple-200">Current, upcoming, and completed initiatives driving community transformation.</p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-12 border-b border-purple-100 dark:border-purple-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap gap-3 justify-center">
            @foreach (['' => 'All', 'current' => 'Current', 'upcoming' => 'Upcoming', 'completed' => 'Completed'] as $value => $label)
                <a href="{{ route('projects.index', $value ? ['status' => $value] : []) }}"
                   class="px-5 py-2 rounded-full text-sm font-semibold {{ $status === $value || (!$status && !$value) ? 'bg-purple-700 text-white' : 'bg-purple-50 dark:bg-purple-900 text-purple-700 dark:text-purple-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($projects as $project)
                <a href="{{ route('projects.show', $project->slug) }}" class="group rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-lg transition-shadow">
                    <div class="h-44 bg-purple-100 dark:bg-purple-900 @if($project->featuredImageUrl()) bg-cover bg-center @endif" @if($project->featuredImageUrl()) style="background-image: url('{{ $project->featuredImageUrl() }}')" @endif></div>
                    <div class="p-6 bg-white dark:bg-purple-900/30">
                        <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ ucfirst($project->status) }}</span>
                        <h2 class="mt-2 font-display font-bold text-xl text-purple-800 dark:text-gold-400 group-hover:underline">{{ $project->title }}</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-purple-200 line-clamp-2">{{ $project->excerpt }}</p>
                        @if ($project->budget)
                            <div class="mt-4">
                                <div class="h-2 rounded-full bg-purple-100 dark:bg-purple-800 overflow-hidden">
                                    <div class="h-full bg-gold-500" style="width: {{ min(100, $project->progress_percent) }}%"></div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-purple-300">{{ $project->progress_percent }}% funded</p>
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500 dark:text-purple-300">No projects found.</p>
            @endforelse
        </div>
    </section>

</x-public-layout>
