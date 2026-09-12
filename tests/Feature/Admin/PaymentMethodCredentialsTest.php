<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\PaymentMethodResource\Pages\CreatePaymentMethod;
use App\Models\PaymentMethod;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentMethodCredentialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_stripe_secret_key_saves_into_config(): void
    {
        $admin = $this->superAdmin();

        Livewire::actingAs($admin)
            ->test(CreatePaymentMethod::class)
            ->fillForm([
                'name' => 'Credit / Debit Card',
                'slug' => 'stripe',
                'type' => 'gateway',
                'driver' => 'stripe',
            ])
            ->fillForm([
                'stripe_secret_key' => 'sk_test_abc123',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $method = PaymentMethod::where('slug', 'stripe')->first();
        $this->assertSame('sk_test_abc123', $method->config['secret_key']);
    }

    public function test_paypal_credentials_save_into_config(): void
    {
        $admin = $this->superAdmin();

        Livewire::actingAs($admin)
            ->test(CreatePaymentMethod::class)
            ->fillForm([
                'name' => 'PayPal',
                'slug' => 'paypal',
                'type' => 'gateway',
                'driver' => 'paypal',
            ])
            ->fillForm([
                'paypal_client_id' => 'client-abc',
                'paypal_secret' => 'secret-xyz',
                'paypal_sandbox' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $method = PaymentMethod::where('slug', 'paypal')->first();
        $this->assertSame('client-abc', $method->config['client_id']);
        $this->assertSame('secret-xyz', $method->config['secret']);
        $this->assertFalse($method->config['sandbox']);
    }

    public function test_bank_transfer_manual_method_needs_no_credentials(): void
    {
        $admin = $this->superAdmin();

        Livewire::actingAs($admin)
            ->test(CreatePaymentMethod::class)
            ->fillForm([
                'name' => 'Bank Transfer',
                'slug' => 'bank-transfer',
                'type' => 'manual',
                'instructions' => 'Wire to Account #12345, Routing #67890, Example Bank.',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('payment_methods', [
            'slug' => 'bank-transfer',
            'type' => 'manual',
        ]);
    }

    private function superAdmin(): User
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        return $user;
    }
}
