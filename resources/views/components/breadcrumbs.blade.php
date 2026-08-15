@props(['items' => []])

<nav aria-label="Breadcrumb" class="bg-purple-50 dark:bg-purple-900/30 border-b border-purple-100 dark:border-purple-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <ol class="flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-purple-300" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ url('/') }}" itemprop="item" class="hover:text-purple-700 dark:hover:text-gold-400">
                    <span itemprop="name">Home</span>
                </a>
                <meta itemprop="position" content="1">
            </li>
            @foreach ($items as $index => $item)
                <li>&rsaquo;</li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    @if (!$loop->last && isset($item['url']))
                        <a href="{{ $item['url'] }}" itemprop="item" class="hover:text-purple-700 dark:hover:text-gold-400">
                            <span itemprop="name">{{ $item['label'] }}</span>
                        </a>
                    @else
                        <span itemprop="name" class="text-purple-800 dark:text-gold-400 font-medium">{{ $item['label'] }}</span>
                    @endif
                    <meta itemprop="position" content="{{ $index + 2 }}">
                </li>
            @endforeach
        </ol>
    </div>
</nav>
