@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Pesanan #{{ $order->id }}
            </h2>

            <p class="text-muted mb-0">
                Detail produk dan pembayaran pesanan
            </p>
        </div>

        <a
            href="{{ route('orders.index') }}"
            class="btn btn-outline-secondary rounded-pill px-4"
        >
            Kembali
        </a>

    </div>


    {{-- RINGKASAN PESANAN --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <small class="text-muted d-block mb-2">
                        Status Pesanan
                    </small>

                    @if ($order->status === 'paid')

                        <span class="badge bg-success rounded-pill px-3 py-2">
                            Dibayar
                        </span>

                    @elseif ($order->status === 'cancelled')

                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            Dibatalkan
                        </span>

                    @else

                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                            Pending
                        </span>

                    @endif

                </div>


                <div class="col-md-6 text-md-end mt-3 mt-md-0">

                    <small class="text-muted d-block">
                        Total Pesanan
                    </small>

                    <h3 class="fw-bold text-primary mb-0">

                        {{-- DIPERBAIKI: total -> total_amount --}}
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- PRODUK PESANAN --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4 pb-2">

            <h5 class="fw-bold mb-1">
                Produk Pesanan
            </h5>

            <p class="text-muted mb-0">
                Produk yang terdapat dalam pesanan ini
            </p>

        </div>


        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="px-4 py-3">
                            Produk
                        </th>

                        <th>
                            Jumlah
                        </th>

                        <th>
                            Harga
                        </th>

                        <th class="text-end px-4">
                            Subtotal
                        </th>

                    </tr>

                </thead>


                <tbody>

                    {{-- DIPERBAIKI: details -> orderDetails (sesuai nama relasi di model Order) --}}
                    @forelse ($order->orderDetails as $detail)

                        @php
                            $subtotal =
                                $detail->quantity *
                                $detail->unit_price;
                        @endphp

                        <tr>

                            <td class="px-4 py-3">

                                <div class="fw-semibold">
                                    {{ $detail->product->name ?? '-' }}
                                </div>

                                <small class="text-muted">
                                    Produk TokoKita
                                </small>

                            </td>


                            <td>
                                {{ $detail->quantity }}
                            </td>


                            <td>

                                Rp
                                {{ number_format(
                                    $detail->unit_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            <td class="text-end px-4 fw-semibold">

                                Rp
                                {{ number_format(
                                    $subtotal,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center py-5 text-muted"
                            >
                                Belum ada produk dalam pesanan ini.
                            </td>

                        </tr>

                    @endforelse

                </tbody>


                {{-- TOTAL --}}
                <tfoot>

                    <tr>

                        <td
                            colspan="3"
                            class="text-end fw-semibold px-4 py-4"
                        >
                            Total Pesanan
                        </td>

                        <td
                            class="text-end px-4 py-4"
                        >

                            <span class="fs-4 fw-bold text-primary">

                                {{-- DIPERBAIKI: total -> total_amount --}}
                                Rp
                                {{ number_format(
                                    $order->total_amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </span>

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>


    {{-- PEMBAYARAN --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4">

        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">
                Pembayaran
            </h5>


            @if ($order->payment)

                <div class="alert alert-success rounded-3 mb-0">

                    <strong>
                        Pembayaran berhasil.
                    </strong>

                    <br>

                    Metode:
                    {{ $order->payment->method }}

                    <br>

                    Jumlah:
                    Rp
                    {{ number_format(
                        $order->payment->amount,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

            @else

                <p class="text-muted">
                    Pesanan ini belum dibayar.
                </p>

                <a
                    href="{{ route('payment.create', $order) }}"
                    class="btn btn-success rounded-pill px-4"
                >
                    Bayar Sekarang
                </a>

            @endif

        </div>

    </div>

</div>

@endsection