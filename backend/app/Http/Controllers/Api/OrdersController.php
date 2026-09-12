<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Menampilkan semua order milik user yang sedang login.
     */
    public function index(Request $request)
    {
        $orders = Order::with([
            'orderDetails.product.store',
            'payment',
        ])
        ->where('user_id', $request->user()->id)
        ->latest()
        ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    /**
     * Menampilkan detail satu order.
     */
    public function show(Request $request, Order $order)
    {
        // Pastikan order hanya bisa dilihat pemiliknya
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Kamu tidak memiliki akses ke pesanan ini.',
            ], 403);
        }

        $order->load([
            'orderDetails.product.store',
            'payment',
        ]);

        return response()->json([
            'order' => $order,
        ]);
    }
}