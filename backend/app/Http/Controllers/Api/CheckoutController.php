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
    /**
     * Menampilkan isi keranjang untuk checkout.
     */
    public function index(Request $request)
    {
        $items = CartItem::with([
            'product.store',
        ])
        ->where('user_id', $request->user()->id)
        ->get();

        if ($items->isEmpty()) {
            return response()->json([
                'message' => 'Keranjang kamu masih kosong.',
                'items' => [],
                'total' => 0,
            ]);
        }

        $items->each(function ($item) {
            $item->subtotal = $item->product->price * $item->quantity;
        });

        $total = $items->sum('subtotal');

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * Membuat order dari isi keranjang.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Keranjang kamu masih kosong.',
            ], 422);
        }

        // Pastikan semua stok masih tersedia
        foreach ($cartItems as $item) {

            if (!$item->product) {
                return response()->json([
                    'message' => 'Ada produk yang sudah tidak tersedia.',
                ], 422);
            }

            if ($item->quantity > $item->product->stock) {
                return response()->json([
                    'message' => "Stok {$item->product->name} tidak mencukupi.",
                ], 422);
            }
        }

        try {

            $order = DB::transaction(function () use ($user, $cartItems) {

                $total = 0;

                foreach ($cartItems as $item) {
                    $total += $item->product->price * $item->quantity;
                }

                // Buat order
                $order = Order::create([
                    'user_id' => $user->id,
                    'total' => $total,
                    'status' => 'pending',
                ]);

                // Buat detail order
                foreach ($cartItems as $item) {

                    $price = $item->product->price;
                    $subtotal = $price * $item->quantity;

                    $order->orderDetails()->create([
                        'product_id' => $item->product->id,
                        'quantity' => $item->quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);

                    // Kurangi stok
                    $item->product->decrement(
                        'stock',
                        $item->quantity
                    );
                }

                // Hapus keranjang
                CartItem::where('user_id', $user->id)->delete();

                return $order;
            });

            $order->load([
                'orderDetails.product',
            ]);

            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'order' => $order,
            ], 201);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Gagal membuat pesanan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}