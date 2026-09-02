@extends('layouts.app')
@section('title', 'Pesanan Saya')
@section('content')
<h3 class="mb-3">Pesanan Saya</h3>
<table class="table bg-white">
    <thead><tr><th>#</th><th>Status</th><th>Total</th><th>Tanggal</th><th></th></tr></thead>
    <tbody>
        @forelse ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td><span class="badge bg-{{ $order->status === 'paid' ? 'success' : 'warning' }}">{{ $order->status }}</span></td>
                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                <td><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="5">Belum ada pesanan.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection