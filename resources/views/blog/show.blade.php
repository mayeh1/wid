<x-public-layout :title="$post->title" :description="$post->excerpt">

    <section class="relative bg-purple-950 py-20">
        @if ($post->featuredImageUrl())
            <div class="absolute inset-0 opacity-30 bg-cover bg-center" style="background-image: url('{{ $post->featuredImageUrl() }}')"></div>
        @endif
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if ($post->category)
                <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-400">{{ $post->category->name }}</span>
            @endif
            <h1 class="mt-3 font-display font-bold text-4xl text-white">{{ $post->title }}</h1>
            <p class="mt-4 text-sm text-purple-300">
                {{ $post->published_at?->format('F j, Y') }} @if($post->author) &middot; {{ $post->author->name }} @endif
            </p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose dark:prose-invert prose-headings:font-display max-w-none">
                {!! $post->body !!}
            </div>

            @if (!empty($post->tags))
                <div class="mt-8 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-purple-50 dark:bg-purple-900 text-purple-700 dark:text-purple-200">#{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-6">Related Posts</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach ($related as $item)
                        <a href="{{ route('blog.show', $item->slug) }}" class="bg-white dark:bg-purple-950 rounded-2xl p-5 border border-purple-100 dark:border-purple-800 hover:shadow-md transition-shadow">
                            <h3 class="font-semibold text-purple-800 dark:text-gold-400">{{ $item->title }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-6">
                Comments ({{ $comments->count() }})
            </h2>

            <div class="space-y-6 mb-10">
                @forelse ($comments as $comment)
                    <div class="border-b border-purple-100 dark:border-purple-800 pb-4">
                        <p class="font-semibold text-sm text-purple-800 dark:text-gold-400">{{ $comment->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-purple-400">{{ $comment->created_at->format('M j, Y') }}</p>
                        <p class="mt-2 text-sm text-gray-700 dark:text-purple-200">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-purple-300">Be the first to comment.</p>
                @endforelse
            </div>

            @if (session('status'))
                <p class="mb-4 text-sm text-green-700 bg-green-50 dark:bg-green-900/30 dark:text-green-300 rounded-lg p-3">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('blog.comment', $post->slug) }}" class="space-y-3">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" name="name" placeholder="Name" required value="{{ old('name') }}" class="rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
                    <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}" class="rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
                </div>
                <textarea name="body" rows="4" placeholder="Write a comment..." required class="w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">{{ old('body') }}</textarea>
                <button type="submit" class="rounded-full bg-purple-700 px-6 py-3 text-sm font-bold text-white hover:bg-purple-800 transition-colors">
                    Post Comment
                </button>
            </form>
        </div>
    </section>

</x-public-layout>
