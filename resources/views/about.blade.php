<x-public-layout :title="'About Us'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">About Us</p>
            <h1 class="font-display font-bold text-4xl text-white">Where Women Become Legends</h1>
            <p class="mt-6 text-purple-200 leading-relaxed">
                {{ $settings->mission_statement ?? 'Women in Development, Inc. exists to restore dignity, create opportunity, and unlock the potential of women and girls through employment pathways, entrepreneurship, financial education, leadership development, mentorship, scholarships, humanitarian support, and community transformation.' }}
            </p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-3 gap-10">
            <div>
                <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400">Our Mission</h2>
                <p class="mt-3 text-sm text-gray-600 dark:text-purple-200 leading-relaxed">
                    Restore dignity, create opportunity, and unlock the potential of women and girls
                    through education, entrepreneurship, and leadership.
                </p>
            </div>
            <div>
                <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400">Our Vision</h2>
                <p class="mt-3 text-sm text-gray-600 dark:text-purple-200 leading-relaxed">
                    A world where every woman and girl has the knowledge, opportunity, and resources
                    to become self-sufficient and create generational impact.
                </p>
            </div>
            <div>
                <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400">Core Values</h2>
                <ul class="mt-3 text-sm text-gray-600 dark:text-purple-200 space-y-1">
                    <li>Charitable impact before personal interest</li>
                    <li>Dignity, nondiscrimination &amp; respect</li>
                    <li>Transparent financial stewardship</li>
                    <li>Evidence-informed programs</li>
                </ul>
            </div>
        </div>
    </section>

    @if ($founder)
        <section class="bg-purple-50 dark:bg-purple-900/30 py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="uppercase tracking-[0.3em] text-gold-600 dark:text-gold-400 text-xs font-semibold mb-6">Founder</p>
                <a href="{{ route('people.show', $founder->slug) }}">
                    @if ($founder->photoUrl())
                        <img src="{{ $founder->photoUrl() }}" alt="{{ $founder->name }}" class="w-48 aspect-[3/4] rounded-2xl object-cover mx-auto mb-5 shadow-lg hover:opacity-90 transition-opacity">
                    @endif
                    <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 hover:underline">{{ $founder->name }}</h2>
                </a>
                <p class="text-sm text-gray-500 dark:text-purple-300">{{ $founder->role_title }}</p>
                @if ($founder->bio)
                    <p class="mt-4 text-gray-600 dark:text-purple-200 leading-relaxed">{{ $founder->bio }}</p>
                @endif
                @php $founderLinks = $founder->links(); @endphp
                @if (!empty($founderLinks))
                    <div class="mt-5 flex flex-wrap justify-center gap-3">
                        @foreach ($founderLinks as $type => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 rounded-full bg-white dark:bg-purple-800/60 px-3 py-1.5 text-xs font-medium text-purple-700 dark:text-gold-300 hover:bg-gold-100 dark:hover:bg-purple-700 transition-colors shadow-sm">
                                <x-team-link-icon :type="$type" />
                                {{ ucfirst($type) }}
                            </a>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('people.show', $founder->slug) }}" class="mt-6 inline-flex items-center text-sm font-semibold text-purple-700 dark:text-gold-400 hover:underline">
                    Read Full Profile &rarr;
                </a>
            </div>
        </section>
    @endif

    @if ($boardMembers->isNotEmpty())
        <section class="bg-white dark:bg-purple-950 py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400 text-center mb-4">Board of Directors</h2>
                <p class="text-center text-sm text-gray-500 dark:text-purple-300 mb-12">Click a member to read their bio and connect.</p>
                <x-team-member-grid :members="$boardMembers" />
            </div>
        </section>
    @endif

    <section class="bg-purple-50 dark:bg-purple-900/30 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400">Meet Our Team</h2>
            <p class="mt-3 text-gray-600 dark:text-purple-200">Get to know the staff and advisors who bring our mission to life every day.</p>
            <a href="{{ route('team') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-purple-800 dark:bg-gold-500 px-8 py-3.5 text-sm font-bold text-white dark:text-purple-950 hover:bg-purple-700 dark:hover:bg-gold-400 transition-colors">
                Meet Our Team
            </a>
        </div>
    </section>

    @if ($page)
        <section class="bg-white dark:bg-purple-950 py-20">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose dark:prose-invert prose-headings:font-display">
                {!! $page->content !!}
            </div>
        </section>
    @endif

    <section class="bg-purple-800 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display font-bold text-2xl sm:text-3xl text-white">Governance &amp; Transparency</h2>
            <p class="mt-4 text-purple-200">Read our annual reports, financial reports, and governance documents.</p>
            <a href="{{ route('resources') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                View Resources &amp; Reports
            </a>
        </div>
    </section>

</x-public-layout>
