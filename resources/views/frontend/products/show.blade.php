@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="mb-4">
    <a
        href="{{ route('home') }}"
        class="text-decoration-none text-secondary"
    >
        ← Kembali ke katalog
    </a>
</div>


<div class="row g-4">

    {{-- =========================
         PRODUCT DETAIL
    ========================== --}}
    <div class="col-lg-8">

        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">

            {{-- PRODUCT IMAGE --}}
            <div class="relative h-[360px] bg-gradient-to-br from-violet-100 via-purple-50 to-indigo-100 flex items-center justify-center overflow-hidden">

                @if($product->image)

                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover"
                    >

                @else

                    <div class="relative w-32 h-32 bg-white rounded-3xl shadow-lg flex items-center justify-center">

                        <span class="text-6xl font-bold text-violet-500">
                            {{ strtoupper(substr($product->name, 0, 1)) }}
                        </span>

                    </div>

                @endif

            </div>


            {{-- PRODUCT INFO --}}
            <div class="p-4 p-md-5">

                {{-- STORE --}}
                <div class="mb-2">

                    <span class="text-secondary small">
                        Toko
                    </span>

                </div>

                <div class="text-primary fw-semibold mb-3">
                    {{ $product->store->name ?? '-' }}
                </div>


                {{-- NAME --}}
                <h1 class="fw-bold mb-3">
                    {{ $product->name }}
                </h1>


                {{-- PRICE --}}
                <div class="text-primary fw-bold fs-2 mb-3">

                    Rp {{ number_format($product->price, 0, ',', '.') }}

                </div>


                {{-- STOCK --}}
                <div class="mb-4">

                    @if ($product->stock > 0)

                        <span
                            class="badge bg-success-subtle text-success rounded-pill px-3 py-2"
                        >
                            Stok tersedia
                        </span>

                        <span class="text-secondary ms-2">
                            {{ $product->stock }} produk tersedia
                        </span>

                    @else

                        <span
                            class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2"
                        >
                            Stok habis
                        </span>

                    @endif

                </div>


                {{-- DESCRIPTION --}}
                @if ($product->detail)

                    <div class="border-top pt-4">

                        <h5 class="fw-bold mb-3">
                            Deskripsi Produk
                        </h5>

                        <p class="text-secondary leading-7 mb-4">
                            {{ $product->detail->description }}
                        </p>


                        {{-- WEIGHT --}}
                        @if ($product->detail->weight)

                            <div class="d-flex align-items-center gap-2">

                                <span class="text-secondary">
                                    Berat produk:
                                </span>

                                <strong>
                                    {{ $product->detail->weight }} kg
                                </strong>

                            </div>

                        @endif

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================
         BUY PANEL
    ========================== --}}
    <div class="col-lg-4">

        <div
            class="bg-white rounded-4 shadow-sm border p-4 sticky-lg-top"
            style="top: 90px;"
        >

            <h5 class="fw-bold mb-4">
                Beli Produk
            </h5>


            {{-- PRICE --}}
            <div class="d-flex justify-content-between mb-3">

                <span class="text-secondary">
                    Harga satuan
                </span>

                <strong>
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </strong>

            </div>


            {{-- STOCK --}}
            <div class="d-flex justify-content-between mb-4">

                <span class="text-secondary">
                    Stok
                </span>

                <strong>
                    {{ $product->stock }}
                </strong>

            </div>


            @auth

                @if ($product->stock > 0)

                    <form
                        method="POST"
                        action="{{ route('cart.add', $product) }}"
                    >

                        @csrf


                        {{-- QUANTITY --}}
                        <div class="mb-3">

                            <label
                                for="quantity"
                                class="form-label fw-semibold"
                            >
                                Jumlah
                            </label>

                            <input
                                id="quantity"
                                type="number"
                                name="quantity"
                                class="form-control form-control-lg rounded-3"
                                value="1"
                                min="1"
                                max="{{ $product->stock }}"
                                required
                            >

                            <div class="form-text">
                                Maksimal {{ $product->stock }} produk.
                            </div>

                        </div>


                        {{-- ADD CART --}}
                        <button
                            type="submit"
                            class="btn btn-primary w-100 rounded-pill py-3"
                        >
                            Tambah ke Keranjang
                        </button>

                    </form>

                @else

                    <button
                        type="button"
                        class="btn btn-secondary w-100 rounded-pill py-3"
                        disabled
                    >
                        Stok Habis
                    </button>

                @endif

            @else

                <a
                    href="{{ route('login') }}"
                    class="btn btn-primary w-100 rounded-pill py-3"
                >
                    Login untuk Membeli
                </a>


                <p class="text-center text-secondary small mt-3 mb-0">

                    Belum punya akun?

                    <a
                        href="{{ route('register') }}"
                        class="text-primary text-decoration-none fw-semibold"
                    >
                        Daftar sekarang
                    </a>

                </p>

            @endauth

        </div>

    </div>

</div>

@endsection