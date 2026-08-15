<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Payments\GatewayNotConfiguredException;
use App\Payments\PaymentGatewayManager;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DonationForm extends Component
{
    public ?Campaign $campaign = null;

    public ?Project $project = null;

    public string $amount = '50';

    public ?string $customAmount = null;

    public string $frequency = 'one_time';

    public string $donorName = '';

    public string $donorEmail = '';

    public bool $isAnonymous = false;

    public ?int $paymentMethodId = null;

    public array $presetAmounts = [25, 50, 100, 250, 500];

    public function mount(?Campaign $campaign = null, ?Project $project = null): void
    {
        $this->campaign = $campaign;
        $this->project = $project;
        $this->paymentMethodId = PaymentMethod::enabled()->orderBy('order')->value('id');

        if ($user = auth()->user()) {
            $this->donorName = $user->name;
            $this->donorEmail = $user->email;
        }
    }

    public function selectAmount(string $amount): void
    {
        $this->amount = $amount;
        $this->customAmount = null;
    }

    public function getFinalAmountProperty(): float
    {
        return (float) ($this->customAmount ?: $this->amount);
    }

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::enabled()->orderBy('order')->get();
    }

    protected function rules(): array
    {
        return [
            'customAmount' => ['nullable', 'numeric', 'min:1'],
            'amount' => ['required'],
            'frequency' => ['required', 'in:one_time,monthly,annual'],
            'donorName' => ['required_unless:isAnonymous,true', 'nullable', 'string', 'max:255'],
            'donorEmail' => ['required', 'email', 'max:255'],
            'paymentMethodId' => ['required', 'exists:payment_methods,id'],
        ];
    }

    public function donate()
    {
        $this->validate();

        $finalAmount = $this->finalAmount;

        if ($finalAmount < 1) {
            $this->addError('customAmount', 'Please enter a donation amount of at least $1.');

            return;
        }

        $method = PaymentMethod::findOrFail($this->paymentMethodId);

        $donation = Donation::create([
            'payment_method_id' => $method->id,
            'campaign_id' => $this->campaign?->id,
            'project_id' => $this->project?->id,
            'user_id' => auth()->id(),
            'donor_name' => $this->isAnonymous ? 'Anonymous' : $this->donorName,
            'donor_email' => $this->donorEmail,
            'is_anonymous' => $this->isAnonymous,
            'amount' => $finalAmount,
            'frequency' => $this->frequency,
            'status' => 'pending',
        ]);

        if (! $method->isGateway()) {
            return $this->redirect(route('donate.instructions', $donation->receipt_number), navigate: false);
        }

        try {
            $manager = app(PaymentGatewayManager::class);
            $redirectUrl = $manager->driverFor($method)->initiate($donation, $method);

            return $this->redirect($redirectUrl, navigate: false);
        } catch (GatewayNotConfiguredException $e) {
            Log::warning('Payment gateway not configured: '.$e->getMessage());
            $this->addError('paymentMethodId', $e->getMessage());
            $donation->update(['status' => 'failed', 'notes' => $e->getMessage()]);
        } catch (\Throwable $e) {
            Log::error('Donation checkout failed', ['exception' => $e]);
            $this->addError('paymentMethodId', 'Something went wrong starting checkout. Please try again or choose a different payment method.');
            $donation->update(['status' => 'failed', 'notes' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.donation-form');
    }
}
