<x-public-layout :title="'Donate'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Make an Impact</p>
            <h1 class="font-display font-bold text-4xl text-white">
                @if ($campaign)
                    Support: {{ $campaign->title }}
                @elseif ($project)
                    Support: {{ $project->title }}
                @else
                    Donate Now
                @endif
            </h1>
            <p class="mt-6 text-purple-200">
                Your gift funds employment pathways, entrepreneurship, financial literacy, leadership
                development, mentorship, scholarships, and humanitarian support.
            </p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($campaign)
                <div class="mb-8 bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6">
                    <div class="flex justify-between text-sm font-semibold text-purple-800 dark:text-gold-400 mb-2">
                        <span>${{ number_format($campaign->raisedAmount(), 0) }} raised</span>
                        <span>Goal: ${{ number_format($campaign->target_amount, 0) }}</span>
                    </div>
                    <div class="h-3 rounded-full bg-purple-100 dark:bg-purple-800 overflow-hidden">
                        <div class="h-full bg-gold-500" style="width: {{ $campaign->progressPercent() }}%"></div>
                    </div>
                </div>
            @endif

            <livewire:donation-form :campaign="$campaign" :project="$project" />
        </div>
    </section>

</x-public-layout>
