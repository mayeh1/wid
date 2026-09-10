@props(['members'])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($members as $member)
        <a href="{{ route('people.show', $member->slug) }}"
           class="group rounded-2xl border border-purple-100 dark:border-purple-800 bg-white dark:bg-purple-900/30 overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
            <div class="aspect-[3/4] w-full overflow-hidden bg-purple-50 dark:bg-purple-800">
                @if ($member->photoUrl())
                    <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
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
                <span class="text-xs font-semibold text-purple-500 dark:text-gold-400 flex items-center gap-1 flex-shrink-0 group-hover:gap-1.5 transition-all">
                    View
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                        <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        </a>
    @endforeach
</div>
