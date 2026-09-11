<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class PaymentController extends Controller
{
    // Menampilkan data order untuk halaman pembayaran
    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if ($order->payment) {
            return response()->json([
                'message' => 'Pesanan ini sudah dibayar.',
                'already_paid' => true,
                'order' => $order,
            ]);
        }

        if (is_null($order->total_amount)) {
            return response()->json([
                'message' => 'Total pesanan tidak ditemukan.',
            ], 422);
        }

        return response()->json(['order' => $order]);
    }

    // Menyimpan metode pembayaran
    public function store(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'method' => ['required', 'in:Transfer Bank,COD,E-Wallet'],
        ]);

        if ($order->payment) {
            return response()->json(['message' => 'Pesanan ini sudah dibayar.'], 422);
        }

        if (is_null($order->total_amount)) {
            return response()->json(['message' => 'Total pesanan tidak ditemukan.'], 422);
        }

        Payment::create([
            'order_id' => $order->id,
            'method' => $data['method'],
            'amount' => $order->total_amount,
        ]);

        $order->update(['status' => 'paid']);

        return response()->json([
            'message' => 'Pembayaran berhasil!',
            'order' => $order->fresh(),
        ]);
    }

    // Generate QR code (akses via <img>, jadi tidak bisa pakai auth:sanctum biasa)
    public function qrCode(Order $order)
    {
        $content = "TokoKita | Order #{$order->id} | Total: Rp "
            . number_format($order->total_amount, 0, ',', '.');

        $builder = new Builder(
            writer: new PngWriter(),
            data: $content,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $result = $builder->build();

        return response($result->getString(), 200)
            ->header('Content-Type', $result->getMimeType());
    }
}