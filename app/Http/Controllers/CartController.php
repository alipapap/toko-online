<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = collect($cart)->map(function ($qty, $productId) use ($products) {
            $product = $products->get($productId);
            return $product ? [
                'product' => $product,
                'quantity' => $qty,
                'subtotal' => $qty * $product->price,
            ] : null;
        })->filter();

        $total = $items->sum('subtotal');

        return view('frontend.cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('quantity', 1));
        $cart = session('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $qty;
        session(['cart' => $cart]);

        return back()->with('success', $product->name . ' ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('quantity', 1));
        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id] = min($qty, $product->stock);
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}