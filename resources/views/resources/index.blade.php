<x-public-layout :title="'Resources & Reports'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Transparency</p>
            <h1 class="font-display font-bold text-4xl text-white">Resources &amp; Reports</h1>
            <p class="mt-6 text-purple-200">Annual reports, financial reports, policies, and governance documents.</p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            @php
                $categoryLabels = [
                    'annual_report' => 'Annual Reports',
                    'financial_report' => 'Financial Reports',
                    'policy' => 'Policies',
                    'governance_document' => 'Governance Documents',
                ];
            @endphp
            @forelse ($downloads as $category => $items)
                <div>
                    <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-4">{{ $categoryLabels[$category] ?? $category }}</h2>
                    <div class="divide-y divide-purple-100 dark:divide-purple-800 border border-purple-100 dark:border-purple-800 rounded-xl overflow-hidden">
                        @foreach ($items as $doc)
                            <a href="{{ $doc->fileUrl() }}" target="_blank" rel="noopener" class="flex items-center justify-between px-5 py-4 hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-colors">
                                <div>
                                    <p class="font-semibold text-sm text-purple-800 dark:text-gold-400">{{ $doc->title }}@if($doc->year) ({{ $doc->year }})@endif</p>
                                    @if ($doc->description)
                                        <p class="text-xs text-gray-500 dark:text-purple-300 mt-1">{{ $doc->description }}</p>
                                    @endif
                                </div>
                                <span class="text-xs font-bold text-gold-600 dark:text-gold-400">Download &darr;</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-purple-300">No documents published yet.</p>
            @endforelse
        </div>
    </section>

</x-public-layout>
