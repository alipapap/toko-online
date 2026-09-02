<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran.
     */
    public function create(Order $order)
    {
        // Pastikan order milik user yang sedang login
        abort_unless($order->user_id === auth()->id(), 403);

        // Jika order sudah memiliki pembayaran
        if ($order->payment) {
            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Pesanan ini sudah dibayar.');
        }

        // Pastikan total order tersedia
        if (is_null($order->total)) {
            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'Total pesanan tidak ditemukan. Silakan hubungi admin.');
        }

        return view('frontend.payment.create', compact('order'));
    }

    /**
     * Menyimpan pembayaran.
     */
    public function store(Request $request, Order $order)
    {
        // Pastikan order milik user yang sedang login
        abort_unless($order->user_id === auth()->id(), 403);

        // Validasi metode pembayaran
        $data = $request->validate([
            'method' => [
                'required',
                'in:Transfer Bank,COD,E-Wallet'
            ],
        ]);

        // Cegah pembayaran kedua
        if ($order->payment) {
            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Pesanan ini sudah dibayar.');
        }

        // Pastikan total order tidak NULL
        if (is_null($order->total)) {
            return redirect()
                ->route('orders.show', $order)
                ->with(
                    'error',
                    'Total pesanan tidak ditemukan. Pembayaran tidak dapat diproses.'
                );
        }

        // Simpan pembayaran
        Payment::create([
            'order_id' => $order->id,
            'method'   => $data['method'],
            'amount'   => $order->total,
        ]);

        // Ubah status order menjadi paid
        $order->update([
            'status' => 'paid',
        ]);

        // Kembali ke halaman detail order
        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Pembayaran berhasil!');
    }
}