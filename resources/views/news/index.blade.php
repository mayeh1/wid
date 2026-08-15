<x-public-layout :title="'News'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Newsroom</p>
            <h1 class="font-display font-bold text-4xl text-white">News &amp; Announcements</h1>
            <p class="mt-6 text-purple-200">Press releases, announcements, and media from Women in Development.</p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @forelse ($posts as $post)
                <a href="{{ route('news.show', $post->slug) }}" class="block group bg-white dark:bg-purple-900/30 rounded-2xl p-6 border border-purple-100 dark:border-purple-800 hover:shadow-md transition-shadow">
                    <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ str_replace('_', ' ', $post->type) }}</span>
                    <h2 class="mt-2 font-display font-bold text-xl text-purple-800 dark:text-gold-400 group-hover:underline">{{ $post->title }}</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-purple-200">{{ $post->excerpt }}</p>
                    <p class="mt-3 text-xs text-gray-400 dark:text-purple-400">{{ $post->published_at?->format('M j, Y') }}</p>
                </a>
            @empty
                <p class="text-center text-gray-500 dark:text-purple-300">No news yet — check back soon.</p>
            @endforelse

            <div>{{ $posts->links() }}</div>
        </div>
    </section>

</x-public-layout>
