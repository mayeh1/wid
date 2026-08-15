<x-public-layout :title="'Contact'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Get In Touch</p>
            <h1 class="font-display font-bold text-4xl text-white">Contact Us</h1>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                @if (session('status'))
                    <p class="mb-4 text-sm text-green-700 bg-green-50 dark:bg-green-900/30 dark:text-green-300 rounded-lg p-3">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 dark:text-purple-200">Name</label>
                            <input type="text" name="name" required value="{{ old('name') }}" class="mt-1 w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-900/30 dark:text-white text-sm">
                            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 dark:text-purple-200">Email</label>
                            <input type="email" name="email" required value="{{ old('email') }}" class="mt-1 w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-900/30 dark:text-white text-sm">
                            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 dark:text-purple-200">Phone (optional)</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-900/30 dark:text-white text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 dark:text-purple-200">Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" class="mt-1 w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-900/30 dark:text-white text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-purple-200">Message</label>
                        <textarea name="message" rows="5" required class="mt-1 w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-900/30 dark:text-white text-sm">{{ old('message') }}</textarea>
                        @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-full bg-purple-700 px-8 py-3.5 text-sm font-bold text-white hover:bg-purple-800 transition-colors">
                        Send Message
                    </button>
                </form>
            </div>

            <div class="space-y-8">
                <div>
                    <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-4">Contact Information</h2>
                    <ul class="space-y-3 text-sm text-gray-600 dark:text-purple-200">
                        @if ($settings->contact_email)
                            <li><strong class="text-purple-800 dark:text-gold-400">Email:</strong> {{ $settings->contact_email }}</li>
                        @endif
                        @if ($settings->contact_phone)
                            <li><strong class="text-purple-800 dark:text-gold-400">Phone:</strong> {{ $settings->contact_phone }}</li>
                        @endif
                        @if ($settings->address)
                            <li><strong class="text-purple-800 dark:text-gold-400">Address:</strong> {{ $settings->address }}</li>
                        @endif
                        @if ($settings->office_hours)
                            <li><strong class="text-purple-800 dark:text-gold-400">Hours:</strong> {{ $settings->office_hours }}</li>
                        @endif
                    </ul>
                </div>

                @if ($settings->google_maps_embed_url)
                    <div class="rounded-2xl overflow-hidden aspect-video">
                        <iframe src="{{ $settings->google_maps_embed_url }}" class="w-full h-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                @endif

                <div>
                    <h2 class="font-display font-bold text-xl text-purple-800 dark:text-gold-400 mb-4">FAQs</h2>
                    <a href="{{ route('faqs') }}" class="text-sm font-semibold text-purple-700 dark:text-gold-400 hover:underline">Browse frequently asked questions &rarr;</a>
                </div>
            </div>
        </div>
    </section>

</x-public-layout>
