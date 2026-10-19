<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::published()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->get();

        $categories = $products->pluck('category')->unique()->values();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::published()->where('slug', $slug)->firstOrFail();

        $related = Product::published()
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
