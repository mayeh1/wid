<x-public-layout :title="'Account Pending Approval'">

    <section class="bg-white dark:bg-purple-950 py-24">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if (auth()->user()->isRejected())
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-8 h-8 text-red-600 dark:text-red-400">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400">Account Not Approved</h1>
                <p class="mt-4 text-gray-600 dark:text-purple-200 leading-relaxed">
                    Your account request was not approved. If you believe this is a mistake, please
                    <a href="{{ route('contact') }}" class="text-purple-700 dark:text-gold-400 underline">contact us</a>.
                </p>
            @else
                <div class="w-16 h-16 rounded-full bg-gold-100 dark:bg-gold-900/40 flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-8 h-8 text-gold-600 dark:text-gold-400">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400">Your Account Is Pending Approval</h1>
                <p class="mt-4 text-gray-600 dark:text-purple-200 leading-relaxed">
                    Thanks for signing up! A member of our team reviews new accounts before granting full access
                    to volunteer applications, membership, and your dashboard. You'll be notified once your
                    account has been approved.
                </p>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center rounded-full border-2 border-purple-300 dark:border-purple-700 px-8 py-3 text-sm font-bold text-purple-700 dark:text-gold-400 hover:bg-purple-50 dark:hover:bg-purple-900 transition-colors">
                    Log Out
                </button>
            </form>
        </div>
    </section>

</x-public-layout>
