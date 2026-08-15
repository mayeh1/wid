<div x-data="{ show: localStorage.getItem('wid-cookie-consent') === null }"
     x-show="show" x-cloak x-transition
     class="fixed inset-x-0 bottom-0 z-50 bg-purple-950 border-t border-purple-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center gap-4">
        <p class="text-sm text-purple-200 flex-1">
            We use cookies to improve your experience on our site. By continuing to browse, you agree to our
            <a href="{{ route('pages.show', 'privacy-policy') }}" class="underline hover:text-gold-400">Privacy Policy</a>.
        </p>
        <div class="flex gap-3 shrink-0">
            <button type="button" @click="localStorage.setItem('wid-cookie-consent', 'accepted'); show = false"
                    class="rounded-full bg-gold-500 px-5 py-2 text-xs font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                Accept
            </button>
            <button type="button" @click="localStorage.setItem('wid-cookie-consent', 'dismissed'); show = false"
                    class="rounded-full border border-purple-700 px-5 py-2 text-xs font-semibold text-purple-200 hover:bg-purple-900 transition-colors">
                Dismiss
            </button>
        </div>
    </div>
</div>
