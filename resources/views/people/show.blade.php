<?php
$categoryLabels = [
    'founder' => 'Founder',
    'board_member' => 'Board of Directors',
    'staff' => 'Staff',
    'advisor' => 'Advisor',
];
$backRoute = in_array($person->category, ['founder', 'board_member']) ? route('about') : route('team');
$backLabel = in_array($person->category, ['founder', 'board_member']) ? 'About Us' : 'Our Team';
?>
<x-public-layout :title="$person->name">

    <x-breadcrumbs :items="[
        ['label' => $backLabel, 'url' => $backRoute],
        ['label' => $person->name],
    ]" />

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 items-start">
                <div class="md:col-span-2">
                    @if ($person->photoUrl())
                        <img src="{{ $person->photoUrl() }}" alt="{{ $person->name }}" class="w-full aspect-[3/4] rounded-2xl object-cover shadow-lg">
                    @else
                        <div class="w-full aspect-[3/4] rounded-2xl bg-purple-100 dark:bg-purple-800 flex items-center justify-center text-purple-700 dark:text-gold-400 font-display font-bold text-7xl shadow-lg">
                            {{ mb_substr($person->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div class="md:col-span-3">
                    <p class="uppercase tracking-[0.3em] text-gold-600 dark:text-gold-400 text-xs font-semibold mb-3">
                        {{ $categoryLabels[$person->category] ?? $person->category }}
                    </p>
                    <h1 class="font-display font-bold text-3xl sm:text-4xl text-purple-800 dark:text-gold-400">{{ $person->name }}</h1>
                    <p class="mt-2 text-gray-500 dark:text-purple-300">{{ $person->role_title }}</p>

                    @if ($person->bio)
                        <p class="mt-6 text-gray-600 dark:text-purple-200 leading-relaxed">{{ $person->bio }}</p>
                    @endif

                    @php $links = $person->links(); @endphp
                    @if (!empty($links))
                        <div class="mt-8 flex flex-wrap gap-3">
                            @foreach ($links as $type => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 rounded-full bg-purple-50 dark:bg-purple-800/60 px-4 py-2 text-sm font-medium text-purple-700 dark:text-gold-300 hover:bg-gold-100 dark:hover:bg-purple-700 transition-colors">
                                    <x-team-link-icon :type="$type" />
                                    {{ ucfirst($type) }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($person->email)
                        <p class="mt-6 text-sm text-gray-500 dark:text-purple-300">
                            <a href="mailto:{{ $person->email }}" class="hover:text-purple-700 dark:hover:text-gold-400 underline">{{ $person->email }}</a>
                        </p>
                    @endif

                    <a href="{{ $backRoute }}" class="mt-10 inline-flex items-center text-sm font-semibold text-purple-700 dark:text-gold-400 hover:underline">
                        &larr; Back to {{ $backLabel }}
                    </a>
                </div>
            </div>
        </div>
    </section>

</x-public-layout>
