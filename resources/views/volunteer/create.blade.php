<x-public-layout :title="'Become a Volunteer'">

    <section class="bg-purple-950 py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="uppercase tracking-[0.3em] text-gold-400 text-xs font-semibold mb-4">Join Our Team</p>
            <h1 class="font-display font-bold text-4xl text-white">Become a Volunteer</h1>
            <p class="mt-6 text-purple-200">Share your time and skills to help women and girls in our community thrive.</p>
        </div>
    </section>

    <section class="bg-white dark:bg-purple-950 py-16">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('volunteer.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="text-sm font-semibold text-gray-700 dark:text-purple-200">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-900/30 dark:text-white text-sm">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700 dark:text-purple-200">Skills</label>
                    <input type="text" name="skills" placeholder="e.g. Marketing, Mentoring, Event Planning" value="{{ old('skills') }}" class="mt-1 w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-900/30 dark:text-white text-sm">
                    <p class="text-xs text-gray-400 dark:text-purple-400 mt-1">Separate multiple skills with commas.</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700 dark:text-purple-200">Availability</label>
                    <textarea name="availability" rows="2" placeholder="e.g. Weekday evenings, weekends" class="mt-1 w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-900/30 dark:text-white text-sm">{{ old('availability') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700 dark:text-purple-200">Tell us about yourself</label>
                    <textarea name="bio" rows="4" class="mt-1 w-full rounded-lg border-gray-300 dark:border-purple-700 dark:bg-purple-900/30 dark:text-white text-sm">{{ old('bio') }}</textarea>
                </div>
                <button type="submit" class="w-full rounded-full bg-gold-500 px-8 py-3.5 text-sm font-bold text-purple-950 hover:bg-gold-400 transition-colors">
                    Submit Application
                </button>
            </form>
        </div>
    </section>

</x-public-layout>
