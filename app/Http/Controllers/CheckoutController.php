<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();
        $items = $products->map(fn ($p) => [
            'product' => $p,
            'quantity' => $cart[$p->id],
            'subtotal' => $cart[$p->id] * $p->price,
        ]);
        $total = $items->sum('subtotal');

        return view('frontend.checkout.index', compact('items', 'total'));
    }

    public function store()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        // Validasi stok
        foreach ($cart as $productId => $qty) {
            $product = $products->get($productId);
            if (!$product || $product->stock < $qty) {
                return back()->with('error', 'Stok untuk "' . ($product->name ?? '-') . '" tidak mencukupi.');
            }
        }

        // Hitung total dari isi keranjang
        $total = collect($cart)->sum(function ($qty, $productId) use ($products) {
            return $qty * $products->get($productId)->price;
        });

        $order = DB::transaction(function () use ($cart, $products, $total) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total_amount' => $total,
            ]);

            foreach ($cart as $productId => $qty) {
                $product = $products->get($productId);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                ]);

                $product->decrement('stock', $qty);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('payment.create', $order)->with('success', 'Pesanan dibuat, silakan lanjutkan pembayaran.');
    }
}