<x-public-layout :title="'FAQs'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Help Center</p>
            <h1 class="font-display font-bold text-4xl text-white">Frequently Asked Questions</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            @forelse ($faqs as $category => $items)
                <div>
                    <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-4">{{ $category }}</h2>
                    <div class="space-y-3">
                        @foreach ($items as $faq)
                            <details class="group bg-purple-50 dark:bg-purple-900/30 rounded-xl p-5">
                                <summary class="cursor-pointer font-semibold text-sm text-purple-800 dark:text-gold-400 list-none flex justify-between items-center">
                                    {{ $faq->question }}
                                    <span class="ml-4 text-purple-400 group-open:rotate-45 transition-transform">+</span>
                                </summary>
                                <p class="mt-3 text-sm text-gray-600 dark:text-purple-200">{{ $faq->answer }}</p>
                            </details>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-purple-300">No FAQs yet.</p>
            @endforelse
        </div>
    </section>

</x-public-layout>
