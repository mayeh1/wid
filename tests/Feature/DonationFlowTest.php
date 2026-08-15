<?php

namespace Tests\Feature;

use App\Livewire\DonationForm;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DonationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_manual_payment_method_creates_a_pending_donation_and_redirects_to_instructions(): void
    {
        $method = PaymentMethod::create([
            'name' => 'Bank Transfer',
            'slug' => 'bank-transfer',
            'type' => 'manual',
            'instructions' => 'Wire to account 12345.',
            'is_enabled' => true,
        ]);

        Livewire::test(DonationForm::class)
            ->set('amount', '100')
            ->set('donorName', 'Jane Doe')
            ->set('donorEmail', 'jane@example.com')
            ->set('paymentMethodId', $method->id)
            ->call('donate')
            ->assertRedirect(route('donate.instructions', Donation::first()->receipt_number));

        $this->assertDatabaseHas('donations', [
            'donor_email' => 'jane@example.com',
            'amount' => 100,
            'status' => 'pending',
            'payment_method_id' => $method->id,
        ]);
    }

    public function test_anonymous_donation_stores_anonymous_donor_name(): void
    {
        $method = PaymentMethod::create([
            'name' => 'Bank Transfer', 'slug' => 'bank-transfer', 'type' => 'manual', 'is_enabled' => true,
        ]);

        Livewire::test(DonationForm::class)
            ->set('amount', '25')
            ->set('isAnonymous', true)
            ->set('donorEmail', 'anon@example.com')
            ->set('paymentMethodId', $method->id)
            ->call('donate');

        $this->assertDatabaseHas('donations', [
            'donor_email' => 'anon@example.com',
            'donor_name' => 'Anonymous',
            'is_anonymous' => 1,
        ]);
    }

    public function test_donation_requires_a_valid_email(): void
    {
        $method = PaymentMethod::create([
            'name' => 'Bank Transfer', 'slug' => 'bank-transfer', 'type' => 'manual', 'is_enabled' => true,
        ]);

        Livewire::test(DonationForm::class)
            ->set('amount', '25')
            ->set('donorName', 'Jane Doe')
            ->set('donorEmail', 'not-an-email')
            ->set('paymentMethodId', $method->id)
            ->call('donate')
            ->assertHasErrors(['donorEmail']);

        $this->assertDatabaseCount('donations', 0);
    }

    public function test_campaign_progress_reflects_only_completed_donations(): void
    {
        $campaign = Campaign::create([
            'title' => 'Test Campaign', 'slug' => 'test-campaign', 'target_amount' => 1000,
        ]);

        Donation::factory()->create(['campaign_id' => $campaign->id, 'amount' => 300, 'status' => 'completed']);
        Donation::factory()->create(['campaign_id' => $campaign->id, 'amount' => 200, 'status' => 'pending']);

        $this->assertEquals(300.0, $campaign->raisedAmount());
        $this->assertEquals(30, $campaign->progressPercent());
    }

    public function test_receipt_download_requires_completed_status(): void
    {
        $donation = Donation::factory()->create(['status' => 'pending']);

        $this->get(route('donations.receipt', $donation->receipt_number))->assertNotFound();
    }

    public function test_completed_donation_receipt_downloads_a_pdf(): void
    {
        $donation = Donation::factory()->create(['status' => 'completed']);

        $this->get(route('donations.receipt', $donation->receipt_number))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
