<x-public-layout :title="'Checkout'">

    <section class="bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display font-bold text-3xl text-white">Checkout</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:checkout />
        </div>
    </section>

</x-public-layout>
