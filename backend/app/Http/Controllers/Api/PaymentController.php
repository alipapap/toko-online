<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Menampilkan data order untuk halaman pembayaran
    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if ($order->payment && $order->payment->payment_status === 'paid') {
            return response()->json([
                'message' => 'Pesanan ini sudah dibayar.',
                'already_paid' => true,
                'order' => $order->fresh('payment'),
            ]);
        }

        if (is_null($order->total)) {
            return response()->json([
                'message' => 'Total pesanan tidak ditemukan.',
            ], 422);
        }

        return response()->json([
            'order' => $order->fresh('payment'),
        ]);
    }

    // Membuat Snap Token Midtrans
    public function store(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if (is_null($order->total)) {
            return response()->json([
                'message' => 'Total pesanan tidak ditemukan.',
            ], 422);
        }

        if ($order->payment && $order->payment->payment_status === 'paid') {
            return response()->json([
                'message' => 'Pesanan ini sudah dibayar.',
            ], 422);
        }

        // ID transaksi harus unik
        $midtransOrderId = 'TOKOKITA-' . $order->id;

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $order->total,
            ],

            'customer_details' => [
                'first_name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $payment = $order->payment ?? new Payment();

            $payment->order_id = $order->id;
            $payment->method = 'Midtrans';
            $payment->amount = $order->total;
            $payment->snap_token = $snapToken;
            $payment->payment_status = 'pending';
            $payment->save();

            // Jangan ubah menjadi paid di sini!
            $order->update([
                'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Snap Token berhasil dibuat.',
                'snap_token' => $snapToken,
                'order' => $order->fresh('payment'),
            ]);

        } catch (\Exception $e) {

            Log::error('Midtrans Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal membuat pembayaran Midtrans.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Notification dari Midtrans
    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $midtransOrderId = $notification->order_id;

            // TOKOKITA-23 → 23
            $orderId = (int) str_replace('TOKOKITA-', '', $midtransOrderId);

            $order = Order::find($orderId);

            if (!$order) {
                return response()->json([
                    'message' => 'Order tidak ditemukan.',
                ], 404);
            }

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            $payment = $order->payment ?? new Payment();

            $payment->order_id = $order->id;
            $payment->method = $notification->payment_type ?? 'Midtrans';
            $payment->amount = $notification->gross_amount ?? $order->total;
            $payment->transaction_id = $notification->transaction_id ?? null;
            $payment->payment_type = $notification->payment_type ?? null;

            if (
                $transactionStatus === 'settlement' ||
                (
                    $transactionStatus === 'capture' &&
                    $fraudStatus === 'accept'
                )
            ) {
                $payment->payment_status = 'paid';
                $payment->paid_at = now();

                $order->update([
                    'status' => 'paid',
                ]);

            } elseif ($transactionStatus === 'pending') {

                $payment->payment_status = 'pending';

                $order->update([
                    'status' => 'pending',
                ]);

            } elseif (
                $transactionStatus === 'deny' ||
                $transactionStatus === 'cancel' ||
                $transactionStatus === 'expire'
            ) {

                $payment->payment_status = 'failed';

                $order->update([
                    'status' => 'failed',
                ]);
            }

            $payment->save();

            return response()->json([
                'message' => 'Notification berhasil diproses.',
            ]);

        } catch (\Exception $e) {

            Log::error('Midtrans Notification Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal memproses notification.',
            ], 500);
        }
    }

    // Generate QR code
    public function qrCode(Order $order)
    {
        $content = "TokoKita | Order #{$order->id} | Total: Rp "
            . number_format($order->total, 0, ',', '.');

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