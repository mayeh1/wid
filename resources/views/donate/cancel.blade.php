<x-public-layout :title="'Donation Cancelled'">

    <section class="bg-purple-950 py-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Checkout Cancelled</p>
            <h1 class="font-display font-bold text-4xl text-white">No worries — you can try again anytime.</h1>
            <p class="mt-6 text-purple-200">Your donation was not completed and you have not been charged.</p>

            <div class="mt-8">
                <a href="{{ url('/donate') }}" class="inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                    Try Again
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
