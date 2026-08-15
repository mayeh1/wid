<x-public-layout :title="'Events'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Get Involved</p>
            <h1 class="font-display font-bold text-4xl text-white">Events</h1>
            <p class="mt-6 text-purple-200">Join us at upcoming gatherings, workshops, and fundraisers.</p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 mb-8">Upcoming Events</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($upcoming as $event)
                    <a href="{{ route('events.show', $event->slug) }}" class="group rounded-2xl overflow-hidden border border-purple-100 dark:border-purple-800 hover:shadow-lg transition-shadow">
                        <div class="h-40 bg-purple-100 dark:bg-purple-900 @if($event->featuredImageUrl()) bg-cover bg-center @endif" @if($event->featuredImageUrl()) style="background-image: url('{{ $event->featuredImageUrl() }}')" @endif></div>
                        <div class="p-6 bg-white dark:bg-purple-900/30">
                            <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-600 dark:text-gold-400">{{ $event->starts_at->format('M j, Y') }}</span>
                            <h3 class="mt-2 font-display font-bold text-lg text-purple-800 dark:text-gold-400 group-hover:underline">{{ $event->title }}</h3>
                            @if ($event->location)
                                <p class="mt-2 text-sm text-gray-500 dark:text-purple-300">{{ $event->location }}</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-gray-500 dark:text-purple-300">No upcoming events scheduled — check back soon.</p>
                @endforelse
            </div>
        </div>
    </section>

    @if ($past->isNotEmpty())
        <section class="bg-purple-50 dark:bg-purple-900/30 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-2xl text-purple-800 dark:text-gold-400 mb-8">Past Events</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($past as $event)
                        <a href="{{ route('events.show', $event->slug) }}" class="bg-white dark:bg-purple-950 rounded-2xl p-5 border border-purple-100 dark:border-purple-800 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="text-xs text-gray-400 dark:text-purple-400">{{ $event->starts_at->format('M j, Y') }}</span>
                            <h3 class="mt-1 font-semibold text-purple-800 dark:text-gold-400">{{ $event->title }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-public-layout>
