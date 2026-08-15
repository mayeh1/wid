<x-public-layout :title="$event->title">

    <section class="relative bg-purple-950 py-20">
        @if ($event->featuredImageUrl())
            <div class="absolute inset-0 opacity-30 bg-cover bg-center" style="background-image: url('{{ $event->featuredImageUrl() }}')"></div>
        @endif
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block text-xs font-bold uppercase tracking-wide text-gold-400">{{ $event->starts_at->format('l, F j, Y \a\t g:i A') }}</span>
            <h1 class="mt-3 font-display font-bold text-4xl text-white">{{ $event->title }}</h1>
            @if ($event->location)
                <p class="mt-3 text-purple-300 text-sm">{{ $event->location }}</p>
            @endif
        </div>
    </section>

    @unless ($event->isPast())
        <section class="bg-purple-800 py-8"
                  x-data="{ target: new Date('{{ $event->starts_at->toIso8601String() }}').getTime(), d:0,h:0,m:0,s:0,
                             tick() { let diff = Math.max(0, this.target - Date.now()); this.d = Math.floor(diff/86400000); this.h = Math.floor(diff/3600000)%24; this.m = Math.floor(diff/60000)%60; this.s = Math.floor(diff/1000)%60; } }"
                  x-init="tick(); setInterval(() => tick(), 1000)">
            <div class="max-w-2xl mx-auto px-4 flex items-center justify-center gap-6 sm:gap-10 text-white text-center">
                <div><p class="font-display font-bold text-3xl" x-text="d">0</p><p class="text-xs uppercase text-purple-300">Days</p></div>
                <div><p class="font-display font-bold text-3xl" x-text="h">0</p><p class="text-xs uppercase text-purple-300">Hours</p></div>
                <div><p class="font-display font-bold text-3xl" x-text="m">0</p><p class="text-xs uppercase text-purple-300">Minutes</p></div>
                <div><p class="font-display font-bold text-3xl" x-text="s">0</p><p class="text-xs uppercase text-purple-300">Seconds</p></div>
            </div>
        </section>
    @endunless

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2">
                @if ($event->description)
                    <div class="prose dark:prose-invert prose-headings:font-display max-w-none">
                        {!! $event->description !!}
                    </div>
                @endif

                @if ($event->google_maps_embed_url)
                    <div class="mt-8 rounded-2xl overflow-hidden aspect-video">
                        <iframe src="{{ $event->google_maps_embed_url }}" class="w-full h-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                @endif
            </div>

            <div>
                @if ($event->registration_url)
                    <a href="{{ $event->registration_url }}" target="_blank" rel="noopener" class="block text-center rounded-full bg-gold-500 px-6 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                        Get Tickets
                    </a>
                @elseif (!$event->isPast())
                    <div class="bg-purple-50 dark:bg-purple-900/30 rounded-2xl p-6">
                        <h3 class="font-display font-bold text-purple-800 dark:text-gold-400 mb-4">RSVP for this event</h3>

                        @if (session('status'))
                            <p class="mb-4 text-sm text-green-700 bg-green-50 dark:bg-green-900/30 dark:text-green-300 rounded-lg p-3">{{ session('status') }}</p>
                        @endif

                        <form method="POST" action="{{ route('events.register', $event->slug) }}" class="space-y-3">
                            @csrf
                            <input type="text" name="name" placeholder="Full name" required value="{{ old('name') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
                            <input type="email" name="email" placeholder="Email address" required value="{{ old('email') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
                            <input type="tel" name="phone" placeholder="Phone (optional)" value="{{ old('phone') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
                            <input type="number" name="guests" min="1" max="20" value="1" placeholder="Guests"
                                   class="w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-950 dark:text-white text-sm">
                            @error('email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            <button type="submit" class="w-full rounded-full bg-purple-700 px-6 py-3 text-sm font-bold text-white hover:bg-purple-800 transition-colors">
                                RSVP Now
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </section>

</x-public-layout>
