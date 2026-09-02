<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderDetails.product', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('frontend.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load(['orderDetails.product', 'payment']);

        return view('frontend.orders.show', compact('order'));
    }
}