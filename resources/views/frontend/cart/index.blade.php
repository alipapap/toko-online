@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')

<div class="mb-4">
    <h1 class="fw-bold mb-1">
        Keranjang Belanja
    </h1>
    <p class="text-secondary mb-0">
        Periksa kembali produk sebelum melanjutkan ke checkout.
    </p>
</div>

@if ($items->isEmpty())

    {{-- =========================
         EMPTY CART
    ========================== --}}
    <div class="bg-white rounded-4 shadow-sm border p-5 text-center">
        <div
            class="mx-auto mb-4 w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center"
        >
            <span class="text-3xl fw-bold text-slate-400">
                0
            </span>
        </div>

        <h3 class="fw-bold mb-2">
            Keranjang masih kosong
        </h3>

        <p class="text-secondary mb-4">
            Belum ada produk yang kamu tambahkan ke keranjang.
        </p>

        <a
            href="{{ route('home') }}"
            class="btn btn-primary rounded-pill px-4"
        >
            Mulai Belanja
        </a>
    </div>

@else

    <div class="row g-4">

        {{-- =========================
             CART ITEMS
        ========================== --}}
        <div class="col-lg-8">
            <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
                @foreach ($items as $item)
                    <div class="p-4 border-bottom">
                        <div class="row align-items-center g-4">
                            {{-- PRODUCT --}}
                            <div class="col-md-5">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="flex-shrink-0 w-20 h-20 rounded-3 bg-gradient-to-br from-violet-100 to-indigo-100 flex items-center justify-center"
                                    >
                                        <span class="text-2xl fw-bold text-violet-500">
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

                            {{-- PRICE --}}
                            <div class="col-md-2">
                                <small class="text-secondary d-block mb-1">
                                    Harga
                                </small>
                                <strong>
                                    Rp {{ number_format($item['product']->price, 0, ',', '.') }}
                                </strong>
                            </div>

                            {{-- QUANTITY --}}
                            <div class="col-md-3">
                                <small class="text-secondary d-block mb-1">
                                    Jumlah
                                </small>
                                <form
                                    method="POST"
                                    action="{{ route('cart.update', $item['product']) }}"
                                    class="d-flex gap-2"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="number"
                                        name="quantity"
                                        value="{{ $item['quantity'] }}"
                                        min="1"
                                        max="{{ $item['product']->stock }}"
                                        class="form-control"
                                        required
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-outline-secondary"
                                    >
                                        Ubah
                                    </button>
                                </form>
                            </div>

                            {{-- DELETE --}}
                            <div class="col-md-2 text-md-end">
                                <form
                                    method="POST"
                                    action="{{ route('cart.remove', $item['product']) }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-link text-danger text-decoration-none p-0"
                                    >
                                        🗑️ Hapus Item
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- SUBTOTAL --}}
                        <div class="text-end mt-3">
                            <small class="text-secondary">
                                Subtotal
                            </small>
                            <div class="fw-bold text-primary">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- =========================
             SUMMARY
        ========================== --}}
        <div class="col-lg-4">
            <div
                class="bg-white rounded-4 shadow-sm border p-4 sticky-lg-top"
                style="top: 90px;"
            >
                <h5 class="fw-bold mb-4">
                    Ringkasan Belanja
                </h5>

                {{-- TOTAL ITEM --}}
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary">
                        Jumlah item
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

                {{-- CHECKOUT --}}
                <a
                    href="{{ route('checkout.index') }}"
                    class="btn btn-primary w-100 rounded-pill py-3 mt-4"
                >
                    Lanjut Checkout
                </a>

                {{-- CONTINUE SHOPPING --}}
                <a
                    href="{{ route('home') }}"
                    class="btn btn-light w-100 rounded-pill py-3 mt-2"
                >
                    Lanjut Belanja
                </a>
            </div>
        </div>

    </div>

@endif

@endsection
