<x-public-layout :title="'Fundraising Campaigns'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Fundraising</p>
            <h1 class="font-display font-bold text-4xl text-white">Campaigns</h1>
            <p class="mt-6 text-purple-200">Support a specific cause and see your impact in real time.</p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($campaigns as $campaign)
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="group rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-lg transition-shadow">
                    <div class="h-44 bg-purple-100 dark:bg-purple-900 @if($campaign->featuredImageUrl()) bg-cover bg-center @endif" @if($campaign->featuredImageUrl()) style="background-image: url('{{ $campaign->featuredImageUrl() }}')" @endif></div>
                    <div class="p-6 bg-white dark:bg-purple-900/30">
                        @if ($campaign->category)
                            <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ $campaign->category }}</span>
                        @endif
                        <h2 class="mt-2 font-display font-bold text-lg text-purple-800 dark:text-gold-400 group-hover:underline">{{ $campaign->title }}</h2>
                        <div class="mt-4">
                            <div class="h-2 rounded-full bg-purple-100 dark:bg-purple-800 overflow-hidden">
                                <div class="h-full bg-gold-500" style="width: {{ $campaign->progressPercent() }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-purple-300">
                                ${{ number_format($campaign->raisedAmount(), 0) }} raised of ${{ number_format($campaign->target_amount, 0) }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500 dark:text-purple-300">No active campaigns right now — check back soon, or make a general donation.</p>
            @endforelse
        </div>
    </section>

</x-public-layout>
