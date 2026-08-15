<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ dark: localStorage.getItem('wid-theme') === 'dark' }"
      x-init="$watch('dark', value => { localStorage.setItem('wid-theme', value ? 'dark' : 'light'); })"
      :class="{ 'dark': dark }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name') }} | Women in Development, Inc.</title>
        <meta name="description" content="{{ $description ?? 'Women in Development, Inc. empowers women and girls through employment pathways, entrepreneurship, financial literacy, leadership development, mentorship, scholarships, and humanitarian support.' }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|playfair-display:600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-gray-900 dark:bg-purple-950 dark:text-gray-100 transition-colors">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:bg-white focus:text-purple-700 focus:px-4 focus:py-2 focus:rounded">
            Skip to content
        </a>

        <div x-data="{ mobileMenuOpen: false }" class="min-h-screen flex flex-col">
            {{-- Header --}}
            <header class="sticky top-0 z-40 bg-white/95 dark:bg-purple-950/95 backdrop-blur border-b border-gray-100 dark:border-purple-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-20">
                        <a href="{{ url('/') }}" wire:navigate class="flex items-center gap-3 shrink-0">
                            <x-application-logo class="h-12 w-auto" />
                            <span class="hidden sm:flex flex-col leading-tight">
                                <span class="font-display font-bold text-lg text-purple-700 dark:text-gold-400">Women in Development</span>
                                <span class="text-xs tracking-wide uppercase text-gold-600 dark:text-gold-500">Where Women Become Legends</span>
                            </span>
                        </a>

                        <nav class="hidden lg:flex items-center gap-8">
                            <a href="{{ url('/') }}" class="text-sm font-semibold text-gray-700 hover:text-purple-700 dark:text-gray-200 dark:hover:text-gold-400">Home</a>
                            <a href="{{ url('/about') }}" class="text-sm font-semibold text-gray-700 hover:text-purple-700 dark:text-gray-200 dark:hover:text-gold-400">About</a>
                            <a href="{{ url('/programs') }}" class="text-sm font-semibold text-gray-700 hover:text-purple-700 dark:text-gray-200 dark:hover:text-gold-400">Programs</a>
                            <a href="{{ url('/projects') }}" class="text-sm font-semibold text-gray-700 hover:text-purple-700 dark:text-gray-200 dark:hover:text-gold-400">Projects</a>
                            <a href="{{ url('/events') }}" class="text-sm font-semibold text-gray-700 hover:text-purple-700 dark:text-gray-200 dark:hover:text-gold-400">Events</a>
                            <a href="{{ url('/blog') }}" class="text-sm font-semibold text-gray-700 hover:text-purple-700 dark:text-gray-200 dark:hover:text-gold-400">Blog</a>
                            <a href="{{ url('/contact') }}" class="text-sm font-semibold text-gray-700 hover:text-purple-700 dark:text-gray-200 dark:hover:text-gold-400">Contact</a>
                        </nav>

                        <div class="flex items-center gap-3">
                            <button @click="dark = !dark" type="button"
                                    class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-purple-900"
                                    aria-label="Toggle dark mode">
                                <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                                <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </button>

                            <a href="{{ url('/donate') }}"
                               class="hidden sm:inline-flex items-center rounded-full bg-gold-500 px-5 py-2.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors shadow-sm">
                                Donate Now
                            </a>

                            <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                                    class="lg:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-purple-900"
                                    aria-label="Toggle menu">
                                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Mobile menu --}}
                <div x-show="mobileMenuOpen" x-cloak x-transition class="lg:hidden border-t border-gray-100 dark:border-purple-900 bg-white dark:bg-purple-950">
                    <nav class="px-4 py-4 flex flex-col gap-1">
                        <a href="{{ url('/') }}" class="px-3 py-2 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-purple-900">Home</a>
                        <a href="{{ url('/about') }}" class="px-3 py-2 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-purple-900">About</a>
                        <a href="{{ url('/programs') }}" class="px-3 py-2 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-purple-900">Programs</a>
                        <a href="{{ url('/projects') }}" class="px-3 py-2 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-purple-900">Projects</a>
                        <a href="{{ url('/events') }}" class="px-3 py-2 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-purple-900">Events</a>
                        <a href="{{ url('/blog') }}" class="px-3 py-2 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-purple-900">Blog</a>
                        <a href="{{ url('/contact') }}" class="px-3 py-2 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-purple-900">Contact</a>
                        <a href="{{ url('/donate') }}" class="mt-2 inline-flex justify-center items-center rounded-full bg-gold-500 px-5 py-2.5 text-sm font-bold text-purple-950">Donate Now</a>
                    </nav>
                </div>
            </header>

            {{-- Page content --}}
            <main id="main-content" class="flex-1">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="bg-purple-950 text-purple-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <x-application-logo class="h-10 w-auto" />
                            <span class="font-display font-bold text-white">Women in Development</span>
                        </div>
                        <p class="text-sm text-purple-300 leading-relaxed">
                            Empowering women and girls through employment pathways, entrepreneurship,
                            financial literacy, leadership development, mentorship, scholarships, and
                            humanitarian support.
                        </p>
                        <p class="text-xs text-purple-400 mt-4">An Indiana 501(c)(3) nonprofit corporation.</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wide text-gold-400 mb-4">Quick Links</h3>
                        <ul class="space-y-2 text-sm text-purple-200">
                            <li><a href="{{ url('/about') }}" class="hover:text-gold-400">About Us</a></li>
                            <li><a href="{{ url('/programs') }}" class="hover:text-gold-400">Programs</a></li>
                            <li><a href="{{ url('/projects') }}" class="hover:text-gold-400">Projects</a></li>
                            <li><a href="{{ url('/events') }}" class="hover:text-gold-400">Events</a></li>
                            <li><a href="{{ url('/blog') }}" class="hover:text-gold-400">Blog</a></li>
                            <li><a href="{{ url('/resources') }}" class="hover:text-gold-400">Resources &amp; Reports</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wide text-gold-400 mb-4">Get Involved</h3>
                        <ul class="space-y-2 text-sm text-purple-200">
                            <li><a href="{{ url('/donate') }}" class="hover:text-gold-400">Donate</a></li>
                            <li><a href="{{ url('/volunteer') }}" class="hover:text-gold-400">Become a Volunteer</a></li>
                            <li><a href="{{ url('/membership') }}" class="hover:text-gold-400">Become a Member</a></li>
                            <li><a href="{{ url('/success-stories') }}" class="hover:text-gold-400">Success Stories</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wide text-gold-400 mb-4">Contact</h3>
                        <ul class="space-y-2 text-sm text-purple-200">
                            <li>hello@womenindevelopmentempire.org</li>
                            <li>Indiana, USA</li>
                        </ul>
                        <div class="flex gap-3 mt-4">
                            <a href="#" aria-label="Facebook" class="p-2 rounded-full bg-purple-900 hover:bg-gold-500 hover:text-purple-950"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 10-11.5 9.9v-7H8v-2.9h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.3c-1.2 0-1.6.8-1.6 1.6v1.9H16l-.4 2.9h-2.1v7A10 10 0 0022 12z"/></svg></a>
                            <a href="#" aria-label="Instagram" class="p-2 rounded-full bg-purple-900 hover:bg-gold-500 hover:text-purple-950"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c2.7 0 3.1 0 4.1.06 1 .05 1.7.2 2.3.45.6.25 1.1.6 1.6 1.1.5.5.85 1 1.1 1.6.24.6.4 1.3.45 2.3.05 1 .06 1.4.06 4.1s0 3.1-.06 4.1c-.05 1-.2 1.7-.45 2.3-.25.6-.6 1.1-1.1 1.6-.5.5-1 .85-1.6 1.1-.6.24-1.3.4-2.3.45-1 .05-1.4.06-4.1.06s-3.1 0-4.1-.06c-1-.05-1.7-.2-2.3-.45-.6-.25-1.1-.6-1.6-1.1-.5-.5-.85-1-1.1-1.6-.24-.6-.4-1.3-.45-2.3C2 15.1 2 14.7 2 12s0-3.1.06-4.1c.05-1 .2-1.7.45-2.3.25-.6.6-1.1 1.1-1.6.5-.5 1-.85 1.6-1.1.6-.24 1.3-.4 2.3-.45C8.9 2 9.3 2 12 2zm0 5a5 5 0 100 10 5 5 0 000-10zm0 8.2a3.2 3.2 0 110-6.4 3.2 3.2 0 010 6.4zm5.2-8.4a1.2 1.2 0 100-2.4 1.2 1.2 0 000 2.4z"/></svg></a>
                            <a href="#" aria-label="LinkedIn" class="p-2 rounded-full bg-purple-900 hover:bg-gold-500 hover:text-purple-950"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6.94 5a2 2 0 11-4-.002 2 2 0 014 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-3.96 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.68-2.91V8.48z"/></svg></a>
                        </div>
                    </div>
                </div>

                <div class="border-t border-purple-900">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-purple-400">
                        <p>&copy; {{ now()->year }} Women in Development, Inc. All rights reserved.</p>
                        <p>Empowering Women. Transforming Lives. Building Legacies.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
