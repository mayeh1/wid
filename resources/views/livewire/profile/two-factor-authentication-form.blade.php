<div>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Two-Factor Authentication
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        Add additional security to your account using two-factor authentication.
    </p>

    @if ($user->hasEnabledTwoFactorAuthentication())
        <div class="mt-4 flex items-center gap-2 text-sm text-green-700 dark:text-green-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            Two-factor authentication is enabled.
        </div>

        @if ($showingRecoveryCodes)
            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Recovery Codes</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Store these in a secure place. Each code can only be used once.</p>
                <div class="grid grid-cols-2 gap-2 font-mono text-sm text-gray-800 dark:text-gray-200">
                    @foreach ($user->recoveryCodes() as $recoveryCode)
                        <div>{{ $recoveryCode }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-4 flex gap-3">
            <button wire:click="regenerateRecoveryCodes" type="button" class="text-sm text-gray-600 dark:text-gray-400 underline">
                Regenerate Recovery Codes
            </button>
            <button wire:click="disable" type="button" class="text-sm text-red-600 dark:text-red-400 underline">
                Disable
            </button>
        </div>
    @elseif ($showingConfirmation)
        <div class="mt-4">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Scan the QR code below with your authenticator app (e.g. Google Authenticator, Authy), then enter
                the generated code to confirm.
            </p>
            <div class="w-48 h-48 bg-white p-2 rounded-lg">
                {!! $user->twoFactorQrCodeSvg() !!}
            </div>

            <div class="mt-4 max-w-xs">
                <x-input-label for="code" value="Authentication Code" />
                <x-text-input wire:model="code" id="code" class="block mt-1 w-full" type="text" inputmode="numeric" />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <x-primary-button wire:click="confirm" class="mt-4">Confirm</x-primary-button>
        </div>
    @else
        <x-primary-button wire:click="enable" class="mt-4">Enable Two-Factor Authentication</x-primary-button>
    @endif
</div>
