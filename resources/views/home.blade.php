<x-public-layout :title="'Home'">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-purple-950">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900 via-purple-950 to-purple-950"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, #D4AF37 0, transparent 35%), radial-gradient(circle at 80% 60%, #8347A8 0, transparent 40%);"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 sm:py-36 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs sm:text-sm font-semibold mb-6">
                Where Women Become Legends
            </p>
            <h1 class="font-display font-bold text-4xl sm:text-5xl lg:text-6xl text-white leading-tight max-w-4xl mx-auto">
                Empowering Women. Transforming Lives. Building Legacies.
            </h1>
            <p class="mt-6 text-lg text-purple-200 max-w-2xl mx-auto">
                Women in Development, Inc. equips women and girls with the skills, capital, and
                confidence to become employable, entrepreneurial, and financially self-sufficient —
                for themselves and the generations that follow.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/donate') }}"
                   class="inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors shadow-lg shadow-gold-500/20 w-full sm:w-auto">
                    Donate Now
                </a>
                <a href="{{ url('/volunteer') }}"
                   class="inline-flex items-center justify-center rounded-full border-2 border-purple-300 px-8 py-3.5 text-sm font-bold text-white hover:bg-white hover:text-purple-950 transition-colors w-full sm:w-auto">
                    Become a Volunteer
                </a>
                <a href="{{ url('/about') }}"
                   class="inline-flex items-center justify-center rounded-full px-8 py-3.5 text-sm font-bold text-gold-300 hover:text-gold-200 transition-colors w-full sm:w-auto">
                    Join Our Mission &rarr;
                </a>
            </div>
        </div>
    </section>

    {{-- Mission strip --}}
    <section class="bg-white dark:bg-purple-950 py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display font-bold text-3xl text-purple-800 dark:text-gold-400">Our Mission</h2>
            <p class="mt-5 text-lg text-gray-600 dark:text-purple-200 leading-relaxed">
                We restore dignity, create opportunity, and unlock the potential of women and girls
                through employment pathways, entrepreneurship, financial literacy, leadership
                development, mentorship, scholarships, humanitarian support, and community
                transformation.
            </p>
        </div>
    </section>

    {{-- Pillars --}}
    <section class="bg-purple-50 dark:bg-purple-900/30 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ([
                    ['title' => 'Entrepreneurship', 'desc' => 'Business training and startup capital for women-led ventures.'],
                    ['title' => 'Financial Literacy', 'desc' => 'Practical money management and financial intelligence skills.'],
                    ['title' => 'Leadership Development', 'desc' => 'Mentorship and training that multiplies community capacity.'],
                    ['title' => 'Scholarships', 'desc' => 'Educational access that opens doors for the next generation.'],
                ] as $pillar)
                    <div class="bg-white dark:bg-purple-950 rounded-2xl p-6 shadow-sm border border-purple-100 dark:border-purple-800">
                        <div class="w-10 h-10 rounded-full bg-gold-100 dark:bg-gold-500/20 flex items-center justify-center mb-4">
                            <span class="w-2.5 h-2.5 rounded-full bg-gold-500"></span>
                        </div>
                        <h3 class="font-display font-bold text-lg text-purple-800 dark:text-gold-400">{{ $pillar['title'] }}</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-purple-200">{{ $pillar['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA banner --}}
    <section class="bg-purple-800 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display font-bold text-2xl sm:text-3xl text-white">
                Join us in building legacies that outlast a lifetime.
            </h2>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/donate') }}" class="inline-flex items-center justify-center rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors w-full sm:w-auto">
                    Donate Now
                </a>
                <a href="{{ url('/membership') }}" class="inline-flex items-center justify-center rounded-full border-2 border-purple-300 px-8 py-3.5 text-sm font-bold text-white hover:bg-white hover:text-purple-950 transition-colors w-full sm:w-auto">
                    Become a Member
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
