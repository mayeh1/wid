<x-public-layout :title="$page->title" :description="$page->meta_description ?? $page->excerpt">

    <section class="bg-purple-950 py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display font-bold text-3xl text-white">{{ $page->title }}</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose dark:prose-invert prose-headings:font-display max-w-none">
            {!! $page->content !!}
        </div>
    </section>

</x-public-layout>
