<x-public-layout :title="'Become a Member'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Join Us</p>
            <h1 class="font-display font-bold text-4xl text-white">Become a Member</h1>
            <p class="mt-6 text-purple-200">Choose a membership level and become part of our community of changemakers.</p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('membership.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @forelse ($levels as $level)
                        <label class="cursor-pointer">
                            <input type="radio" name="membership_level_id" value="{{ $level->id }}" class="peer sr-only" required {{ $loop->first ? 'checked' : '' }}>
                            <div class="rounded-2xl border-2 border-purple-100 dark:border-purple-800 peer-checked:border-gold-500 peer-checked:bg-gold-50 dark:peer-checked:bg-gold-500/10 p-6 h-full transition-colors">
                                <h3 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400">{{ $level->name }}</h3>
                                <p class="mt-2 font-bold text-2xl text-purple-800 dark:text-gold-400">${{ number_format($level->annual_price, 0) }}<span class="text-sm font-normal text-gray-400">/yr</span></p>
                                @if ($level->description)
                                    <p class="mt-3 text-sm text-gray-600 dark:text-purple-200">{{ $level->description }}</p>
                                @endif
                                @if (!empty($level->perks))
                                    <ul class="mt-4 space-y-1 text-sm text-gray-600 dark:text-purple-200">
                                        @foreach ($level->perks as $perk)
                                            <li>&bull; {{ $perk }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </label>
                    @empty
                        <p class="col-span-full text-center text-gray-500 dark:text-purple-300">Membership levels are being finalized — check back soon.</p>
                    @endforelse
                </div>

                @if ($levels->isNotEmpty())
                    <div class="mt-10 text-center">
                        <button type="submit" class="rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                            Join Now
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </section>

</x-public-layout>
