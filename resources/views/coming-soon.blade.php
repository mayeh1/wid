<x-public-layout :title="$pageTitle">
    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-28 text-center">
        <p class="uppercase tracking-[0.3em] text-gold-600 dark:text-gold-400 text-xs font-semibold mb-4">Coming Soon</p>
        <h1 class="font-display font-bold text-3xl sm:text-4xl text-purple-800 dark:text-gold-400">{{ $pageTitle }}</h1>
        <p class="mt-5 text-gray-600 dark:text-purple-200">
            This section is under construction as we build out the full Women in Development site.
            Check back soon.
        </p>
        <a href="{{ url('/') }}" class="mt-8 inline-flex items-center justify-center rounded-full bg-purple-700 px-6 py-3 text-sm font-bold text-white hover:bg-purple-800 transition-colors">
            &larr; Back to Home
        </a>
    </section>
</x-public-layout>
