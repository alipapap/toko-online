<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = CartItem::with(['product.store'])
            ->where('user_id', $request->user()->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Keranjang masih kosong.'], 422);
        }

        $items = $cartItems->map(function ($item) {
            return [
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

    public function store(Request $request)
    {
        $user = $request->user();
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Keranjang masih kosong.'], 422);
        }

        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return response()->json([
                    'message' => 'Stok untuk "' . $item->product->name . '" tidak mencukupi.',
                ], 422);
            }
        }

        $total = $cartItems->sum(fn ($item) => $item->quantity * $item->product->price);

        $order = DB::transaction(function () use ($cartItems, $user, $total) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => $total,
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            CartItem::where('user_id', $user->id)->delete();

            return $order;
        });

        return response()->json([
            'message' => 'Pesanan dibuat, silakan lanjutkan pembayaran.',
            'order' => $order,
        ], 201);
    }
}