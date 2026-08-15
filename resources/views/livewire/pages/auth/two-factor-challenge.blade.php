<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $code = '';

    public string $recoveryCode = '';

    public bool $usingRecoveryCode = false;

    public function mount(): void
    {
        if (! Session::has('login.two_factor.id')) {
            $this->redirect(route('login'), navigate: true);
        }
    }

    public function verify(): void
    {
        $user = User::find(Session::get('login.two_factor.id'));

        if (! $user) {
            $this->redirect(route('login'), navigate: true);

            return;
        }

        $valid = $this->usingRecoveryCode
            ? $user->verifyRecoveryCode($this->recoveryCode)
            : $user->verifyTwoFactorCode($this->code);

        if (! $valid) {
            $this->addError($this->usingRecoveryCode ? 'recoveryCode' : 'code', 'The provided code is invalid.');

            return;
        }

        Auth::login($user, Session::pull('login.two_factor.remember', false));
        Session::forget('login.two_factor.id');
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        @if ($usingRecoveryCode)
            Please confirm access to your account by entering one of your emergency recovery codes.
        @else
            Please confirm access to your account by entering the authentication code from your authenticator app.
        @endif
    </p>

    <form wire:submit="verify">
        @if ($usingRecoveryCode)
            <div>
                <x-input-label for="recoveryCode" value="Recovery Code" />
                <x-text-input wire:model="recoveryCode" id="recoveryCode" class="block mt-1 w-full" type="text" autofocus />
                <x-input-error :messages="$errors->get('recoveryCode')" class="mt-2" />
            </div>
        @else
            <div>
                <x-input-label for="code" value="Authentication Code" />
                <x-text-input wire:model="code" id="code" class="block mt-1 w-full" type="text" inputmode="numeric" autofocus />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>
        @endif

        <div class="flex items-center justify-between mt-4">
            <button type="button" wire:click="$set('usingRecoveryCode', {{ $usingRecoveryCode ? 'false' : 'true' }})"
                    class="text-sm text-gray-600 dark:text-gray-400 underline">
                {{ $usingRecoveryCode ? 'Use an authentication code instead' : 'Use a recovery code instead' }}
            </button>

            <x-primary-button>Verify</x-primary-button>
        </div>
    </form>
</div>
