<x-filament-panels::page>
    @php
        $donations = $this->getDonationTotals();
        $volunteers = $this->getVolunteerTotals();
        $campaigns = $this->getCampaigns();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <x-filament::section>
            <p class="text-xs uppercase text-gray-500">Total Raised</p>
            <p class="text-2xl font-bold text-purple-700 dark:text-gold-400">${{ number_format($donations['total_raised'], 2) }}</p>
        </x-filament::section>
        <x-filament::section>
            <p class="text-xs uppercase text-gray-500">This Month</p>
            <p class="text-2xl font-bold text-purple-700 dark:text-gold-400">${{ number_format($donations['this_month'], 2) }}</p>
        </x-filament::section>
        <x-filament::section>
            <p class="text-xs uppercase text-gray-500">Pending Donations</p>
            <p class="text-2xl font-bold text-purple-700 dark:text-gold-400">{{ $donations['pending'] }}</p>
        </x-filament::section>
        <x-filament::section>
            <p class="text-xs uppercase text-gray-500">Volunteer Hours</p>
            <p class="text-2xl font-bold text-purple-700 dark:text-gold-400">{{ number_format($volunteers['total_hours'], 1) }}</p>
        </x-filament::section>
    </div>

    <x-filament::section heading="Campaign Performance" class="mt-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-gray-500">
                    <th class="pb-2">Campaign</th>
                    <th class="pb-2">Goal</th>
                    <th class="pb-2">Raised</th>
                    <th class="pb-2">Progress</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($campaigns as $campaign)
                    <tr class="border-t border-gray-100 dark:border-gray-700">
                        <td class="py-2">{{ $campaign->title }}</td>
                        <td class="py-2">${{ number_format($campaign->target_amount, 0) }}</td>
                        <td class="py-2">${{ number_format($campaign->raisedAmount(), 0) }}</td>
                        <td class="py-2">{{ $campaign->progressPercent() }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-gray-500">No active campaigns.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-filament::section>

    <div class="mt-6">
        <x-filament::button tag="a" href="{{ route('admin.reports.summary-pdf') }}" icon="heroicon-o-document-arrow-down">
            Download Full PDF Report
        </x-filament::button>
    </div>
</x-filament-panels::page>
