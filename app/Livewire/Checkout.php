<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Payments\GatewayNotConfiguredException;
use App\Payments\PaymentGatewayManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Checkout extends Component
{
    public string $customerName = '';

    public string $customerEmail = '';

    public ?int $paymentMethodId = null;

    public function mount(): void
    {
        if (empty(session()->get('cart', []))) {
            $this->redirect(route('products.index'), navigate: false);
            return;
        }

        $this->paymentMethodId = PaymentMethod::enabled()->orderBy('order')->value('id');

        if ($user = auth()->user()) {
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
        }
    }

    public function getCartProperty(): array
    {
        return session()->get('cart', []);
    }

    public function getTotalProperty(): float
    {
        return collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::enabled()->orderBy('order')->get();
    }

    protected function rules(): array
    {
        return [
            'customerName' => ['required', 'string', 'max:255'],
            'customerEmail' => ['required', 'email', 'max:255'],
            'paymentMethodId' => ['required', 'exists:payment_methods,id'],
        ];
    }

    public function placeOrder()
    {
        $this->validate();

        $cart = $this->cart;

        if (empty($cart)) {
            return $this->redirect(route('products.index'), navigate: false);
        }

        $method = PaymentMethod::findOrFail($this->paymentMethodId);

        $order = DB::transaction(function () use ($cart, $method) {
            $total = 0;

            $order = Order::create([
                'user_id' => auth()->id(),
                'payment_method_id' => $method->id,
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'total' => 0,
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);

                if (! $product || ! $product->is_published) {
                    continue;
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ]);

                if (! $product->isDigital() && $product->stock !== null) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            $order->update(['total' => $total]);

            return $order;
        });

        session()->forget('cart');
        $this->dispatch('cart-updated');

        if (! $method->isGateway()) {
            return $this->redirect(route('orders.instructions', $order->order_number), navigate: false);
        }

        try {
            $manager = app(PaymentGatewayManager::class);
            $driver = $manager->driverFor($method);

            $donation = $this->createProxyDonation($order, $method);
            $redirectUrl = $driver->initiate($donation, $method);

            $order->update(['gateway_reference' => $donation->gateway_reference]);

            return $this->redirect($redirectUrl, navigate: false);
        } catch (GatewayNotConfiguredException $e) {
            Log::warning('Payment gateway not configured: ' . $e->getMessage());
            $this->addError('paymentMethodId', $e->getMessage());
            $order->update(['status' => 'failed', 'notes' => $e->getMessage()]);
        } catch (\Throwable $e) {
            Log::error('Order checkout failed', ['exception' => $e]);
            $this->addError('paymentMethodId', 'Something went wrong starting checkout. Please try again or choose a different payment method.');
            $order->update(['status' => 'failed', 'notes' => $e->getMessage()]);
        }
    }

    private function createProxyDonation(Order $order, PaymentMethod $method): \App\Models\Donation
    {
        return \App\Models\Donation::create([
            'payment_method_id' => $method->id,
            'user_id' => auth()->id(),
            'donor_name' => $order->customer_name,
            'donor_email' => $order->customer_email,
            'amount' => $order->total,
            'frequency' => 'one_time',
            'status' => 'pending',
            'notes' => "Product order #{$order->order_number}",
        ]);
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
