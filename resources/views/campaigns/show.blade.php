<x-public-layout :title="$campaign->title">

    <x-breadcrumbs :items="[
        ['label' => 'Campaigns', 'url' => route('campaigns.index')],
        ['label' => $campaign->title],
    ]" />

    <section class="relative bg-purple-950 py-20">
        @if ($campaign->featuredImageUrl())
            <div class="absolute inset-0 opacity-30 bg-cover bg-center" style="background-image: url('{{ $campaign->featuredImageUrl() }}')"></div>
        @endif
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if ($campaign->category)
                <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-400">{{ $campaign->category }}</span>
            @endif
            <h1 class="mt-3 font-display font-bold text-4xl text-white">{{ $campaign->title }}</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2">
                <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6 mb-8">
                    <div class="flex justify-between text-sm font-semibold text-purple-800 dark:text-gold-400 mb-2">
                        <span>${{ number_format($campaign->raisedAmount(), 0) }} raised</span>
                        <span>Goal: ${{ number_format($campaign->target_amount, 0) }}</span>
                    </div>
                    <div class="h-3 rounded-full bg-purple-100 dark:bg-purple-800 overflow-hidden">
                        <div class="h-full bg-gold-500" style="width: {{ $campaign->progressPercent() }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-purple-300">{{ $campaign->progressPercent() }}% funded</p>
                </div>

                @if ($campaign->description)
                    <div class="prose dark:prose-invert prose-headings:font-display max-w-none">
                        {!! $campaign->description !!}
                    </div>
                @endif

                @if (!empty($campaign->updates))
                    <div class="mt-10">
                        <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-4">Campaign Updates</h2>
                        <ol class="border-l-2 border-purple-200 dark:border-purple-800 pl-6 space-y-4">
                            @foreach ($campaign->updates as $update)
                                <li>
                                    <p class="text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ $update['date'] ?? '' }}</p>
                                    <p class="text-sm text-gray-700 dark:text-purple-200">{{ $update['text'] ?? '' }}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                @if ($donorWall->isNotEmpty())
                    <div class="mt-10">
                        <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-4">Donor Wall</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ($donorWall as $donor)
                                <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-3 text-center">
                                    <p class="text-sm font-semibold text-purple-800 dark:text-gold-400">{{ $donor->donor_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-purple-300">${{ number_format($donor->amount, 0) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <a href="{{ route('donate.campaign', $campaign->slug) }}" class="block text-center rounded-full bg-gold-500 px-6 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                    Donate to This Campaign
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
