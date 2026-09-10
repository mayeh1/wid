@props(['members'])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($members as $member)
        <div
            x-data="{ open: false }"
            class="rounded-2xl border border-purple-100 dark:border-purple-800 bg-white dark:bg-purple-900/30 overflow-hidden transition-shadow"
            :class="open ? 'shadow-lg' : 'shadow-sm'"
        >
            <button type="button" @click="open = !open" class="w-full text-left">
                <div class="aspect-[3/4] w-full overflow-hidden bg-purple-50 dark:bg-purple-800">
                    @if ($member->photoUrl())
                        <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-purple-700 dark:text-gold-400 font-display font-bold text-5xl">
                            {{ mb_substr($member->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="flex items-center gap-3 p-4">
                    <span class="flex-1 min-w-0">
                        <span class="block font-semibold text-purple-800 dark:text-gold-400">{{ $member->name }}</span>
                        <span class="block text-xs text-gray-500 dark:text-purple-300">{{ $member->role_title }}</span>
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-purple-400 dark:text-purple-300 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="px-4 pb-5">
                @if ($member->bio)
                    <p class="text-sm text-gray-600 dark:text-purple-200 leading-relaxed">{{ $member->bio }}</p>
                @endif

                @php $links = $member->links(); @endphp
                @if (!empty($links))
                    <div class="mt-4 flex flex-wrap gap-3">
                        @foreach ($links as $type => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 dark:bg-purple-800/60 px-3 py-1.5 text-xs font-medium text-purple-700 dark:text-gold-300 hover:bg-gold-100 dark:hover:bg-purple-700 transition-colors">
                                <x-team-link-icon :type="$type" />
                                {{ ucfirst($type) }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
