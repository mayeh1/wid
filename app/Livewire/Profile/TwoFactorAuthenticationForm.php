<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TwoFactorAuthenticationForm extends Component
{
    public string $code = '';

    public bool $showingConfirmation = false;

    public bool $showingRecoveryCodes = false;

    public function enable(): void
    {
        $user = Auth::user();

        $user->generateTwoFactorSecret();

        $this->showingConfirmation = true;
    }

    public function confirm(): void
    {
        $user = Auth::user();

        if (! $user->confirmTwoFactorAuthentication($this->code)) {
            $this->addError('code', 'The provided code is invalid.');

            return;
        }

        $this->showingConfirmation = false;
        $this->code = '';
        $this->showingRecoveryCodes = true;
    }

    public function disable(): void
    {
        Auth::user()->disableTwoFactorAuthentication();

        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = false;
    }

    public function regenerateRecoveryCodes(): void
    {
        Auth::user()->regenerateRecoveryCodes();
        $this->showingRecoveryCodes = true;
    }

    public function render()
    {
        return view('livewire.profile.two-factor-authentication-form', [
            'user' => Auth::user()->fresh(),
        ]);
    }
}
