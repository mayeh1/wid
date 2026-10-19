<x-public-layout :title="'Shopping Cart'">

    <section class="bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display font-bold text-3xl text-white">Shopping Cart</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:cart-page />
        </div>
    </section>

</x-public-layout>
