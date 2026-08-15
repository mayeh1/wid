<x-public-layout :title="'Volunteer Dashboard'">

    <section class="bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display font-bold text-3xl text-white">Volunteer Dashboard</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <p class="mb-6 text-sm text-green-700 bg-green-50 dark:bg-green-900/30 dark:text-green-300 rounded-lg p-3">{{ session('status') }}</p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6 text-center">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-purple-300">Status</p>
                    <p class="mt-2 font-display font-bold text-lg
                        @if($volunteer->status === 'approved') text-green-600 dark:text-green-400
                        @elseif($volunteer->status === 'rejected') text-red-600 dark:text-red-400
                        @else text-gold-600 dark:text-gold-400 @endif">
                        {{ ucfirst($volunteer->status) }}
                    </p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6 text-center">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-purple-300">Total Hours</p>
                    <p class="mt-2 font-display font-bold text-lg text-purple-800 dark:text-gold-400">{{ number_format($volunteer->totalHours(), 1) }}</p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6 text-center">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-purple-300">Certificate</p>
                    @if ($volunteer->status === 'approved' && $volunteer->totalHours() > 0)
                        <a href="{{ route('volunteer.certificate') }}" class="mt-2 inline-block text-sm font-bold text-purple-700 dark:text-gold-400 hover:underline">Download &darr;</a>
                    @else
                        <p class="mt-2 text-sm text-gray-400 dark:text-purple-400">Not yet available</p>
                    @endif
                </div>
            </div>

            @if ($volunteer->status === 'pending')
                <p class="text-center text-sm text-gray-500 dark:text-purple-300">
                    Your application is under review. Our Volunteer Manager will be in touch soon.
                </p>
            @endif

            @if ($volunteer->hourLogs->isNotEmpty())
                <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-4">Hour Log</h2>
                <div class="divide-y divide-purple-100 dark:divide-purple-800 border border-purple-100 dark:border-purple-800 rounded-xl overflow-hidden">
                    @foreach ($volunteer->hourLogs->sortByDesc('date') as $log)
                        <div class="flex justify-between px-5 py-3 text-sm">
                            <span class="text-gray-600 dark:text-purple-200">{{ $log->date->format('M j, Y') }} &mdash; {{ $log->activity }}</span>
                            <span class="font-semibold text-purple-800 dark:text-gold-400">{{ $log->hours }} hrs</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</x-public-layout>
