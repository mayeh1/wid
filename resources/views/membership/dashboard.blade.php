<x-public-layout :title="'My Membership'">

    <section class="bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display font-bold text-3xl text-white">My Membership</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <p class="mb-6 text-sm text-green-700 bg-green-50 dark:bg-green-900/30 dark:text-green-300 rounded-lg p-3">{{ session('status') }}</p>
            @endif

            <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-8 text-center">
                <p class="text-xs uppercase tracking-wide text-gold-600 dark:text-gold-400 font-semibold">{{ $membership->level->name }} Member</p>
                <p class="mt-2 font-display font-bold text-2xl
                    @if($membership->isActive()) text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
                    {{ $membership->isActive() ? 'Active' : 'Expired' }}
                </p>
                <p class="mt-4 text-sm text-gray-600 dark:text-purple-200">
                    Member since {{ $membership->started_at->format('F Y') }}<br>
                    {{ $membership->isActive() ? 'Renews' : 'Expired' }} {{ $membership->expires_at->format('F j, Y') }}
                </p>

                <form method="POST" action="{{ route('membership.renew') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="rounded-full bg-purple-700 px-8 py-3 text-sm font-bold text-white hover:bg-purple-800 transition-colors">
                        Renew Membership
                    </button>
                </form>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('resources') }}" class="text-sm font-semibold text-purple-700 dark:text-gold-400 hover:underline">
                    Browse Member Resources &amp; Reports &rarr;
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
