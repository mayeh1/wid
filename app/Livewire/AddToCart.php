<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;

    public int $quantity = 1;

    public function addToCart(): void
    {
        $cart = session()->get('cart', []);

        $id = $this->product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $this->quantity;
        } else {
            $cart[$id] = [
                'product_id' => $id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'quantity' => $this->quantity,
                'image' => $this->product->imageUrl(),
            ];
        }

        session()->put('cart', $cart);

        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: "{$this->product->name} added to cart!");
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
