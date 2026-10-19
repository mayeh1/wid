<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Payments\PaymentGatewayManager;

class OrderController extends Controller
{
    public function cart()
    {
        return view('products.cart');
    }

    public function checkout()
    {
        if (empty(session()->get('cart', []))) {
            return redirect()->route('products.index');
        }

        return view('products.checkout');
    }

    public function instructions(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return view('products.instructions', ['order' => $order->load('paymentMethod', 'items')]);
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($order->status === 'pending' && $order->paymentMethod?->isGateway() && $order->gateway_reference) {
            try {
                $proxyDonation = \App\Models\Donation::where('notes', "Product order #{$order->order_number}")
                    ->where('status', 'pending')
                    ->first();

                if ($proxyDonation) {
                    $proxyDonation->update(['gateway_reference' => $order->gateway_reference]);
                    $manager = app(PaymentGatewayManager::class);

                    if ($manager->driverFor($order->paymentMethod)->verify($proxyDonation, $order->paymentMethod)) {
                        $order->markCompleted();
                        $proxyDonation->markCompleted();
                    }
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return view('products.success', ['order' => $order->fresh()->load('items')]);
    }

    public function cancel(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return view('products.cancel', ['order' => $order]);
    }

    public function download(string $orderNumber, int $itemId)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('status', 'completed')
            ->firstOrFail();

        $item = $order->items()->where('id', $itemId)->firstOrFail();
        $product = $item->product;

        if (! $product || ! $product->isDigital()) {
            abort(404);
        }

        $media = $product->getFirstMedia('download');

        if (! $media) {
            abort(404);
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}
