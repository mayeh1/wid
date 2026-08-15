<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_enable_and_confirm_two_factor_authentication(): void
    {
        $user = User::factory()->create();

        $secret = $user->generateTwoFactorSecret();

        $this->assertFalse($user->hasEnabledTwoFactorAuthentication());

        $validCode = app(Google2FA::class)->getCurrentOtp($secret);

        $this->assertTrue($user->confirmTwoFactorAuthentication($validCode));
        $this->assertTrue($user->fresh()->hasEnabledTwoFactorAuthentication());
    }

    public function test_login_with_two_factor_enabled_requires_challenge(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $secret = $user->generateTwoFactorSecret();
        $validCode = app(Google2FA::class)->getCurrentOtp($secret);
        $user->confirmTwoFactorAuthentication($validCode);

        $response = $this->post('/login', [
            'form' => ['email' => $user->email, 'password' => 'password'],
        ]);

        // Password alone should not establish a full session yet.
        $this->assertGuest();
    }

    public function test_recovery_code_can_be_used_once(): void
    {
        $user = User::factory()->create();
        $secret = $user->generateTwoFactorSecret();
        $validCode = app(Google2FA::class)->getCurrentOtp($secret);
        $user->confirmTwoFactorAuthentication($validCode);

        $recoveryCode = $user->fresh()->recoveryCodes()[0];

        $this->assertTrue($user->verifyRecoveryCode($recoveryCode));
        $this->assertFalse($user->verifyRecoveryCode($recoveryCode));
    }

    public function test_disabling_two_factor_clears_secret(): void
    {
        $user = User::factory()->create();
        $secret = $user->generateTwoFactorSecret();
        $validCode = app(Google2FA::class)->getCurrentOtp($secret);
        $user->confirmTwoFactorAuthentication($validCode);

        $user->disableTwoFactorAuthentication();

        $this->assertFalse($user->fresh()->hasEnabledTwoFactorAuthentication());
    }
}
