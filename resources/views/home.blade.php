<x-public-layout :title="'Home'">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-purple-950"
             @if ($heroSlides->count() > 1) x-data="{ slide: 0, count: {{ $heroSlides->count() }} }" x-init="setInterval(() => slide = (slide + 1) % count, 6000)" @endif>
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900 via-purple-950 to-purple-950"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, #D4AF37 0, transparent 35%), radial-gradient(circle at 80% 60%, #8347A8 0, transparent 40%);"></div>

        @if ($heroSlides->isEmpty())
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 sm:py-36 text-center">
                <p class="uppercase tracking-[0.3em] text-gold-400 text-xs sm:text-sm font-semibold mb-6">
                    Where Women Become Legends
                </p>
                <h1 class="font-display font-bold text-4xl sm:text-5xl lg:text-6xl text-white leading-tight max-w-4xl mx-auto">
                    Empowering Women. Transforming Lives. Building Legacies.
                </h1>
                <p class="mt-6 text-lg text-purple-200 max-w-2xl mx-auto">
                    {{ $settings->mission_statement ?? 'Women in Development, Inc. equips women and girls with the skills, capital, and confidence to become employable, entrepreneurial, and financially self-sufficient — for themselves and the generations that follow.' }}
                </p>

                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ url('/donate') }}" class="inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors shadow-lg shadow-gold-500/20 w-full sm:w-auto">Donate Now</a>
                    <a href="{{ url('/volunteer') }}" class="inline-flex items-center justify-center rounded-full border-2 border-purple-300 px-8 py-3.5 text-sm font-bold text-white hover:bg-white hover:text-purple-950 transition-colors w-full sm:w-auto">Become a Volunteer</a>
                    <a href="{{ url('/about') }}" class="inline-flex items-center justify-center rounded-full px-8 py-3.5 text-sm font-bold text-gold-300 hover:text-gold-200 transition-colors w-full sm:w-auto">Join Our Mission &rarr;</a>
                </div>
            </div>
        @else
            @foreach ($heroSlides as $index => $slide)
                <div x-show="slide === {{ $index }}" x-transition.opacity.duration.700ms
                     class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 sm:py-36 text-center"
                     @if ($slide->backgroundUrl())
                        style="background-image: linear-gradient(to bottom, rgba(31,15,40,.75), rgba(31,15,40,.92)), url('{{ $slide->backgroundUrl() }}'); background-size: cover; background-position: center;"
                     @endif>
                    <p class="uppercase tracking-[0.3em] text-gold-400 text-xs sm:text-sm font-semibold mb-6">Where Women Become Legends</p>
                    <h1 class="font-display font-bold text-4xl sm:text-5xl lg:text-6xl text-white leading-tight max-w-4xl mx-auto">{{ $slide->heading }}</h1>
                    @if ($slide->subheading)
                        <p class="mt-6 text-lg text-purple-200 max-w-2xl mx-auto">{{ $slide->subheading }}</p>
                    @endif
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @if ($slide->cta_label && $slide->cta_url)
                            <a href="{{ $slide->cta_url }}" class="inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors shadow-lg shadow-gold-500/20 w-full sm:w-auto">{{ $slide->cta_label }}</a>
                        @endif
                        <a href="{{ url('/donate') }}" class="inline-flex items-center justify-center rounded-full border-2 border-purple-300 px-8 py-3.5 text-sm font-bold text-white hover:bg-white hover:text-purple-950 transition-colors w-full sm:w-auto">Donate Now</a>
                    </div>
                </div>
            @endforeach
        @endif
    </section>

    {{-- Animated stats --}}
    <section class="bg-purple-900 py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            @foreach ([
                ['label' => 'Women Empowered', 'value' => $settings->women_empowered_count],
                ['label' => 'Scholarships Awarded', 'value' => $settings->scholarships_awarded_count],
                ['label' => 'Communities Reached', 'value' => $settings->communities_reached_count],
                ['label' => 'Projects Completed', 'value' => $settings->projects_completed_count],
            ] as $stat)
                <div>
                    <p class="font-display font-bold text-3xl sm:text-4xl text-gold-400"
                       x-data="{ n: 0 }" x-init="let target = {{ $stat['value'] }}; let step = Math.max(1, Math.ceil(target / 60)); let t = setInterval(() => { n = Math.min(target, n + step); if (n >= target) clearInterval(t); }, 20);"
                       x-text="n.toLocaleString()">0</p>
                    <p class="mt-2 text-sm text-purple-300">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Mission strip --}}
    <section class="bg-white dark:bg-purple-950 py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400">Our Mission</h2>
            <p class="mt-5 text-lg text-gray-600 dark:text-purple-200 leading-relaxed">
                {{ $settings->mission_statement ?? 'We restore dignity, create opportunity, and unlock the potential of women and girls through employment pathways, entrepreneurship, financial literacy, leadership development, mentorship, scholarships, humanitarian support, and community transformation.' }}
            </p>
        </div>
    </section>

    {{-- Featured programs --}}
    @if ($featuredPrograms->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-10">
                    <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400">Our Programs</h2>
                    <a href="{{ route('programs.index') }}" class="text-sm font-semibold text-purple-700 dark:text-gold-400 hover:underline">View all &rarr;</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredPrograms as $program)
                        <a href="{{ route('programs.show', $program->slug) }}" class="group bg-white dark:bg-purple-950 rounded-2xl p-6 shadow-sm border border-purple-100 dark:border-purple-800 hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-full bg-gold-100 dark:bg-gold-500/20 flex items-center justify-center mb-4">
                                <span class="w-2.5 h-2.5 rounded-full bg-gold-500"></span>
                            </div>
                            <h3 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400 group-hover:underline">{{ $program->title }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-purple-200 line-clamp-3">{{ $program->excerpt }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured projects --}}
    @if ($featuredProjects->isNotEmpty())
        <section class="bg-white dark:bg-purple-950 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-10">
                    <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400">Featured Projects</h2>
                    <a href="{{ route('projects.index') }}" class="text-sm font-semibold text-purple-700 dark:text-gold-400 hover:underline">View all &rarr;</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach ($featuredProjects as $project)
                        <a href="{{ route('projects.show', $project->slug) }}" class="group rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-md transition-shadow">
                            <div class="h-40 bg-purple-100 dark:bg-purple-900 @if($project->featuredImageUrl()) bg-cover bg-center @endif" @if($project->featuredImageUrl()) style="background-image: url('{{ $project->featuredImageUrl() }}')" @endif></div>
                            <div class="p-5 bg-white dark:bg-purple-950">
                                <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ ucfirst($project->status) }}</span>
                                <h3 class="mt-1 font-display font-bold text-lg text-purple-800 dark:text-gold-400 group-hover:underline">{{ $project->title }}</h3>
                                @if ($project->budget)
                                    <div class="mt-3 h-2 rounded-full bg-purple-100 dark:bg-purple-800 overflow-hidden">
                                        <div class="h-full bg-gold-500" style="width: {{ min(100, $project->progress_percent) }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400 text-center mb-10">Success Stories</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($testimonials as $t)
                        <div class="bg-white dark:bg-purple-950 rounded-2xl p-6 shadow-sm border border-purple-100 dark:border-purple-800">
                            <p class="text-gray-700 dark:text-purple-100 italic">&ldquo;{{ $t->quote }}&rdquo;</p>
                            <div class="mt-4 flex items-center gap-3">
                                @if ($t->photoUrl())
                                    <img src="{{ $t->photoUrl() }}" alt="{{ $t->name }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-purple-200 dark:bg-purple-800 flex items-center justify-center text-purple-700 dark:text-gold-400 font-bold">{{ mb_substr($t->name, 0, 1) }}</div>
                                @endif
                                <div>
                                    <p class="font-semibold text-sm text-purple-800 dark:text-gold-400">{{ $t->name }}</p>
                                    @if ($t->role)<p class="text-xs text-gray-500 dark:text-purple-300">{{ $t->role }}</p>@endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Partners --}}
    @if ($partners->isNotEmpty())
        <section class="bg-white dark:bg-purple-950 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm font-bold uppercase tracking-wide text-gray-400 dark:text-purple-400 mb-8">Our Partners</p>
                <div class="flex flex-wrap items-center justify-center gap-10">
                    @foreach ($partners as $partner)
                        <a href="{{ $partner->website_url ?: '#' }}" class="opacity-70 hover:opacity-100 transition-opacity" @if($partner->website_url) target="_blank" rel="noopener" @endif>
                            @if ($partner->logoUrl())
                                <img src="{{ $partner->logoUrl() }}" alt="{{ $partner->name }}" class="h-10 w-auto grayscale hover:grayscale-0 transition-all">
                            @else
                                <span class="font-semibold text-gray-500 dark:text-purple-300">{{ $partner->name }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA banner --}}
    <section class="bg-purple-800 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display font-bold text-2xl sm:text-3xl text-white">
                Join us in building legacies that outlast a lifetime.
            </h2>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/donate') }}" class="inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors w-full sm:w-auto">Donate Now</a>
                <a href="{{ url('/membership') }}" class="inline-flex items-center justify-center rounded-full border-2 border-purple-300 px-8 py-3.5 text-sm font-bold text-white hover:bg-white hover:text-purple-950 transition-colors w-full sm:w-auto">Become a Member</a>
            </div>
        </div>
    </section>

</x-public-layout>
