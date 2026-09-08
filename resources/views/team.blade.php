<x-public-layout :title="'Our Team'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Our Team</p>
            <h1 class="font-display font-bold text-4xl text-white">The People Behind the Mission</h1>
            <p class="mt-6 text-purple-200 leading-relaxed">
                Meet the staff and advisors who carry out our programs and support women and girls every day.
            </p>
        </div>
    </section>

    @if ($staff->isNotEmpty())
        <section class="bg-white dark:bg-purple-950 py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400 text-center mb-4">Staff</h2>
                <p class="text-center text-sm text-gray-500 dark:text-purple-300 mb-12">Click a member to read their bio and connect.</p>
                <x-team-member-grid :members="$staff" />
            </div>
        </section>
    @endif

    @if ($advisors->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400 text-center mb-4">Advisors</h2>
                <p class="text-center text-sm text-gray-500 dark:text-purple-300 mb-12">Click a member to read their bio and connect.</p>
                <x-team-member-grid :members="$advisors" />
            </div>
        </section>
    @endif

    @if ($staff->isEmpty() && $advisors->isEmpty())
        <section class="bg-white dark:bg-purple-950 py-20">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 dark:text-purple-300">
                Team member profiles are coming soon.
            </div>
        </section>
    @endif

    <section class="bg-purple-800 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display font-bold text-2xl sm:text-3xl text-white">Want to Meet Our Leadership?</h2>
            <p class="mt-4 text-purple-200">See our founder and Board of Directors on the About page.</p>
            <a href="{{ route('about') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                About Us
            </a>
        </div>
    </section>

</x-public-layout>
