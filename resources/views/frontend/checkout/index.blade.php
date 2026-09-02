@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<div class="mb-4">

    <a
        href="{{ route('cart.index') }}"
        class="text-decoration-none text-secondary small"
    >
        ← Kembali ke keranjang
    </a>


    <h1 class="fw-bold mt-3 mb-1">
        Checkout
    </h1>


    <p class="text-secondary mb-0">
        Periksa pesanan sebelum membuat order.
    </p>

</div>


<div class="row g-4">

    {{-- =========================
         ORDER ITEMS
    ========================== --}}
    <div class="col-lg-8">

        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">

            <div class="p-4 border-bottom">

                <h5 class="fw-bold mb-1">
                    Pesanan Kamu
                </h5>

                <p class="text-secondary small mb-0">
                    Pastikan produk dan jumlahnya sudah benar.
                </p>

            </div>


            @foreach ($items as $item)

                <div class="p-4 border-bottom">

                    <div class="row align-items-center g-3">

                        {{-- PRODUCT --}}
                        <div class="col-md-6">

                            <div class="d-flex align-items-center gap-3">

                                <div
                                    class="flex-shrink-0 w-16 h-16 rounded-3 bg-gradient-to-br from-violet-100 to-indigo-100 flex items-center justify-center"
                                >

                                    <span class="text-xl fw-bold text-violet-500">
                                        {{ strtoupper(substr($item['product']->name, 0, 1)) }}
                                    </span>

                                </div>


                                <div>

                                    <h6 class="fw-bold mb-1">
                                        {{ $item['product']->name }}
                                    </h6>

                                    <small class="text-secondary">
                                        {{ $item['product']->store->name ?? '-' }}
                                    </small>

                                </div>

                            </div>

                        </div>


                        {{-- QUANTITY --}}
                        <div class="col-md-2">

                            <span class="text-secondary small d-block">
                                Jumlah
                            </span>

                            <strong>
                                {{ $item['quantity'] }}
                            </strong>

                        </div>


                        {{-- SUBTOTAL --}}
                        <div class="col-md-4 text-md-end">

                            <span class="text-secondary small d-block">
                                Subtotal
                            </span>

                            <strong class="text-primary">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </strong>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>


    {{-- =========================
         ORDER SUMMARY
    ========================== --}}
    <div class="col-lg-4">

        <div
            class="bg-white rounded-4 shadow-sm border p-4 sticky-lg-top"
            style="top: 90px;"
        >

            <h5 class="fw-bold mb-4">
                Ringkasan Pembayaran
            </h5>


            {{-- ITEM COUNT --}}
            <div class="d-flex justify-content-between mb-3">

                <span class="text-secondary">
                    Jumlah produk
                </span>

                <strong>
                    {{ $items->sum('quantity') }}
                </strong>

            </div>


            {{-- TOTAL --}}
            <div class="border-top pt-3">

                <div
                    class="d-flex justify-content-between align-items-center"
                >

                    <span class="fw-semibold">
                        Total
                    </span>

                    <strong class="fs-4 text-primary">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </strong>

                </div>

            </div>


            {{-- CREATE ORDER --}}
            <form
                method="POST"
                action="{{ route('checkout.store') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-primary w-100 rounded-pill py-3 mt-4"
                >
                    Buat Pesanan
                </button>

            </form>


            {{-- BACK TO CART --}}
            <a
                href="{{ route('cart.index') }}"
                class="btn btn-light w-100 rounded-pill py-3 mt-2"
            >
                Kembali ke Keranjang
            </a>

        </div>

    </div>

</div>

@endsection