<x-public-layout :title="'Programs'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">What We Do</p>
            <h1 class="font-display font-bold text-4xl text-white">Our Programs</h1>
            <p class="mt-6 text-purple-200">
                Employment pathways, entrepreneurship, financial literacy, leadership development,
                mentorship, scholarships, and humanitarian support — built to transform lives.
            </p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($programs as $program)
                    <a href="{{ route('programs.show', $program->slug) }}" class="group rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-lg transition-shadow bg-white dark:bg-purple-900/30">
                        <div class="h-44 bg-purple-100 dark:bg-purple-900 @if($program->featuredImageUrl()) bg-cover bg-center @endif" @if($program->featuredImageUrl()) style="background-image: url('{{ $program->featuredImageUrl() }}')" @endif></div>
                        <div class="p-6">
                            <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ \App\Models\Program::CATEGORIES[$program->category] ?? $program->category }}</span>
                            <h2 class="mt-2 font-display font-bold text-xl text-purple-800 dark:text-gold-400 group-hover:underline">{{ $program->title }}</h2>
                            <p class="mt-2 text-sm text-gray-600 dark:text-purple-200 line-clamp-3">{{ $program->excerpt }}</p>
                            <span class="mt-4 inline-flex items-center text-sm font-semibold text-purple-700 dark:text-gold-400">Learn more &rarr;</span>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-gray-500 dark:text-purple-300">Programs are being added — check back soon.</p>
                @endforelse
            </div>
        </div>
    </section>

</x-public-layout>
