<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

trait HasTwoFactorAuthentication
{
    public function hasEnabledTwoFactorAuthentication(): bool
    {
        return ! is_null($this->two_factor_secret) && ! is_null($this->two_factor_confirmed_at);
    }

    public function generateTwoFactorSecret(): string
    {
        $secret = app(Google2FA::class)->generateSecretKey();

        $this->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode($this->freshRecoveryCodes())),
            'two_factor_confirmed_at' => null,
        ])->save();

        return $secret;
    }

    public function confirmTwoFactorAuthentication(string $code): bool
    {
        if (! $this->two_factor_secret) {
            return false;
        }

        $valid = app(Google2FA::class)->verifyKey(decrypt($this->two_factor_secret), $code);

        if ($valid) {
            $this->forceFill(['two_factor_confirmed_at' => now()])->save();
        }

        return $valid;
    }

    public function verifyTwoFactorCode(string $code): bool
    {
        if (! $this->two_factor_secret) {
            return false;
        }

        return app(Google2FA::class)->verifyKey(decrypt($this->two_factor_secret), $code);
    }

    public function verifyRecoveryCode(string $code): bool
    {
        $codes = $this->recoveryCodes();

        if (! in_array($code, $codes, true)) {
            return false;
        }

        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(array_values(array_diff($codes, [$code])))),
        ])->save();

        return true;
    }

    public function recoveryCodes(): array
    {
        return $this->two_factor_recovery_codes
            ? json_decode(decrypt($this->two_factor_recovery_codes), true)
            : [];
    }

    public function regenerateRecoveryCodes(): void
    {
        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($this->freshRecoveryCodes())),
        ])->save();
    }

    public function disableTwoFactorAuthentication(): void
    {
        $this->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    public function twoFactorQrCodeSvg(): string
    {
        $google2fa = app(Google2FA::class);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $this->email,
            decrypt($this->two_factor_secret)
        );

        $writer = new \BaconQrCode\Writer(
            new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd
            )
        );

        return $writer->writeString($qrCodeUrl);
    }

    private function freshRecoveryCodes(): array
    {
        return collect(range(1, 8))
            ->map(fn () => Str::random(10).'-'.Str::random(10))
            ->all();
    }
}
