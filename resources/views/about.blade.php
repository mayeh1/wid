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
                @if ($founder->photoUrl())
                    <img src="{{ $founder->photoUrl() }}" alt="{{ $founder->name }}" class="w-28 h-28 rounded-full object-cover mx-auto mb-5">
                @endif
                <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400">{{ $founder->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-purple-300">{{ $founder->role_title }}</p>
                @if ($founder->bio)
                    <p class="mt-4 text-gray-600 dark:text-purple-200 leading-relaxed">{{ $founder->bio }}</p>
                @endif
            </div>
        </section>
    @endif

    @if ($boardMembers->isNotEmpty())
        <section class="bg-white dark:bg-purple-950 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400 text-center mb-12">Board of Directors</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($boardMembers as $member)
                        <div class="text-center">
                            @if ($member->photoUrl())
                                <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="w-20 h-20 rounded-full object-cover mx-auto mb-3">
                            @else
                                <div class="w-20 h-20 rounded-full bg-purple-100 dark:bg-purple-800 flex items-center justify-center mx-auto mb-3 text-purple-700 dark:text-gold-400 font-display font-bold text-xl">
                                    {{ mb_substr($member->name, 0, 1) }}
                                </div>
                            @endif
                            <p class="font-semibold text-sm text-purple-800 dark:text-gold-400">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-purple-300">{{ $member->role_title }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($staff->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400 text-center mb-12">Our Team</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($staff as $member)
                        <div class="text-center">
                            @if ($member->photoUrl())
                                <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="w-20 h-20 rounded-full object-cover mx-auto mb-3">
                            @else
                                <div class="w-20 h-20 rounded-full bg-purple-100 dark:bg-purple-800 flex items-center justify-center mx-auto mb-3 text-purple-700 dark:text-gold-400 font-display font-bold text-xl">
                                    {{ mb_substr($member->name, 0, 1) }}
                                </div>
                            @endif
                            <p class="font-semibold text-sm text-purple-800 dark:text-gold-400">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-purple-300">{{ $member->role_title }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

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
