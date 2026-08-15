<x-public-layout :title="'Blog'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Stories &amp; Insights</p>
            <h1 class="font-display font-bold text-4xl text-white">Blog</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-4 gap-10">
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    @forelse ($posts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="group rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-lg transition-shadow">
                            <div class="h-44 bg-purple-100 dark:bg-purple-900 @if($post->featuredImageUrl()) bg-cover bg-center @endif" @if($post->featuredImageUrl()) style="background-image: url('{{ $post->featuredImageUrl() }}')" @endif></div>
                            <div class="p-6 bg-white dark:bg-purple-900/30">
                                @if ($post->category)
                                    <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ $post->category->name }}</span>
                                @endif
                                <h2 class="mt-2 font-display font-bold text-lg text-purple-800 dark:text-gold-400 group-hover:underline">{{ $post->title }}</h2>
                                <p class="mt-2 text-sm text-gray-600 dark:text-purple-200 line-clamp-2">{{ $post->excerpt }}</p>
                                <p class="mt-3 text-xs text-gray-400 dark:text-purple-400">{{ $post->published_at?->format('M j, Y') }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="col-span-full text-gray-500 dark:text-purple-300">No posts yet — check back soon.</p>
                    @endforelse
                </div>

                <div class="mt-10">{{ $posts->links() }}</div>
            </div>

            <aside class="space-y-6">
                <form method="GET" action="{{ route('blog.index') }}">
                    <input type="search" name="q" value="{{ $search }}" placeholder="Search posts..."
                           class="w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
                </form>

                <div>
                    <h3 class="font-display font-bold text-purple-800 dark:text-gold-400 mb-3">Categories</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('blog.index') }}" class="{{ !$categorySlug ? 'font-bold text-purple-700 dark:text-gold-400' : 'text-gray-600 dark:text-purple-200' }} hover:underline">All Posts</a></li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                                   class="{{ $categorySlug === $category->slug ? 'font-bold text-purple-700 dark:text-gold-400' : 'text-gray-600 dark:text-purple-200' }} hover:underline">
                                    {{ $category->name }} ({{ $category->posts_count }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </section>

</x-public-layout>
