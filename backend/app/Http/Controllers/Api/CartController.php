<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = CartItem::with(['product.store'])
            ->where('user_id', $request->user()->id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * $item->product->price,
                ];
            });

        $total = $items->sum('subtotal');

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('quantity', 1));

        $item = CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->quantity += $qty;
            $item->save();
        } else {
            CartItem::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'quantity' => $qty,
            ]);
        }

        return response()->json(['message' => $product->name . ' ditambahkan ke keranjang.']);
    }

    public function update(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('quantity', 1));

        $item = CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->quantity = min($qty, $product->stock);
            $item->save();
        }

        return response()->json(['message' => 'Keranjang diperbarui.']);
    }

    public function remove(Request $request, Product $product)
    {
        CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return response()->json(['message' => 'Produk dihapus dari keranjang.']);
    }
}