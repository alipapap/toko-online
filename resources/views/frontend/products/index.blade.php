@extends('layouts.app')

@section('title', 'Katalog Produk')


@push('styles')

<style>

    /* =====================================================
       HERO
    ====================================================== */

    .hero-tokokita {

        position: relative;

        min-height: 270px;

        padding: 50px 34px;

        border-radius: 24px;

        overflow: hidden;

        background:
            linear-gradient(
                120deg,
                #7c3aed,
                #a855f7,
                #4f46e5
            );

        color: white;

        box-shadow:
            0 15px 30px rgba(79, 70, 229, .18);

    }


    .hero-tokokita::before {

        content: "";

        position: absolute;

        width: 230px;
        height: 230px;

        border-radius: 50%;

        right: -50px;
        top: -70px;

        background: rgba(255,255,255,.10);

    }


    .hero-tokokita::after {

        content: "";

        position: absolute;

        width: 180px;
        height: 180px;

        border-radius: 50%;

        left: -80px;
        bottom: -100px;

        background: rgba(255,255,255,.08);

    }


    .hero-content {

        position: relative;

        z-index: 2;

        max-width: 650px;

    }


    .hero-label {

        display: inline-block;

        padding: 7px 18px;

        border: 1px solid rgba(255,255,255,.7);

        border-radius: 50px;

        font-size: 13px;

        font-weight: 600;

        margin-bottom: 18px;

    }


    .hero-content h1 {

        font-size: 36px;

        font-weight: 800;

        line-height: 1.15;

        margin-bottom: 12px;

    }


    .hero-content p {

        font-size: 15px;

        color: rgba(255,255,255,.85);

        margin-bottom: 20px;

    }


    .hero-button {

        background: white;

        color: #111827;

        border: none;

        border-radius: 50px;

        padding: 10px 22px;

        font-weight: 600;

    }


    .hero-button:hover {

        background: #f1f5f9;

        color: #111827;

    }


    .hero-logo {

        position: absolute;

        right: 90px;

        top: 50%;

        transform: translateY(-50%);

        z-index: 2;

        width: 120px;

        height: 120px;

        border-radius: 50%;

        background: rgba(255,255,255,.10);

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

    }


    .hero-logo strong {

        font-size: 42px;

        line-height: 1;

    }


    .hero-logo small {

        margin-top: 5px;

        color: rgba(255,255,255,.7);

    }


    /* =====================================================
       SEARCH
    ====================================================== */

    .search-box {

        background: white;

        border: 1px solid #e5e7eb;

        border-radius: 16px;

        padding: 14px;

        box-shadow: 0 3px 12px rgba(0,0,0,.05);

    }


    /* =====================================================
       PRODUCT CARD
    ====================================================== */

    .product-card {

        border: 1px solid #e5e7eb;

        border-radius: 16px;

        overflow: hidden;

        background: white;

        height: 100%;

        transition: .2s;

    }


    .product-card:hover {

        transform: translateY(-3px);

        box-shadow: 0 10px 25px rgba(0,0,0,.08);

    }


    .product-image {

        height: 145px;

        display: flex;

        justify-content: center;

        align-items: center;

        background:
            radial-gradient(
                circle at 80% 15%,
                #e9e4ff 0,
                #e9e4ff 20%,
                transparent 21%
            ),
            radial-gradient(
                circle at 10% 90%,
                #e1e7ff 0,
                #e1e7ff 22%,
                transparent 23%
            ),
            #f1efff;

    }


    .product-letter {

        width: 70px;

        height: 70px;

        border-radius: 18px;

        display: flex;

        align-items: center;

        justify-content: center;

        background: white;

        color: #8b5cf6;

        font-size: 25px;

        font-weight: 800;

        box-shadow: 0 5px 12px rgba(0,0,0,.10);

    }


    .product-body {

        padding: 18px;

    }


    .product-store {

        color: #64748b;

        font-size: 12px;

        margin-bottom: 5px;

    }


    .product-name {

        font-weight: 700;

        min-height: 42px;

    }


    .product-price {

        color: #2563eb;

        font-size: 17px;

        font-weight: 800;

    }


    .product-stock {

        color: #64748b;

        font-size: 12px;

    }


    .product-button {

        width: 100%;

        border-radius: 50px;

    }


    @media (max-width: 768px) {

        .hero-tokokita {

            padding: 40px 30px;

        }

        .hero-content h1 {

            font-size: 30px;

        }

        .hero-logo {

            display: none;

        }

    }

</style>

@endpush


@section('content')


{{-- =====================================================
     HERO
====================================================== --}}

<div class="hero-tokokita mb-5">

    <div class="hero-content">

        <span class="hero-label">
            Belanja lebih mudah
        </span>

        <h1>
            Temukan Produk Favoritmu
        </h1>

        <p>
            Temukan berbagai produk pilihan dari toko-toko
            yang tersedia di TokoKita.
        </p>

        <a
            href="#produk"
            class="btn hero-button"
        >
            Mulai Belanja
        </a>

    </div>


    <div class="hero-logo">

        <strong>
            TK
        </strong>

        <small>
            TokoKita
        </small>

    </div>

</div>


{{-- =====================================================
     SEARCH
====================================================== --}}

<div class="mb-4">

    <h6 class="fw-bold mb-1">
        Cari Produk
    </h6>

    <p class="text-secondary small mb-3">
        Gunakan pencarian atau pilih toko untuk menemukan produk.
    </p>


    <form
        method="GET"
        class="search-box"
    >

        <div class="row g-2 align-items-end">

            <div class="col-md-6">

                <label class="form-label small">
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Cari produk..."
                    value="{{ request('q') }}"
                >

            </div>


            <div class="col-md-4">

                <label class="form-label small">
                    Toko
                </label>

                <select
                    name="store_id"
                    class="form-select"
                >

                    <option value="">
                        Semua Toko
                    </option>

                    @foreach ($stores as $store)

                        <option
                            value="{{ $store->id }}"
                            @selected(request('store_id') == $store->id)
                        >
                            {{ $store->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-2">

                <button
                    class="btn btn-primary w-100"
                >
                    Cari
                </button>

            </div>

        </div>

    </form>

</div>


{{-- =====================================================
     PRODUCTS
====================================================== --}}

<div
    id="produk"
    class="mt-5"
>

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h6 class="fw-bold mb-1">
                Produk Pilihan
            </h6>

            <p class="text-secondary small mb-0">
                Pilihan produk yang tersedia saat ini.
            </p>

        </div>


        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">

            {{ $products->total() }}

            Produk

        </span>

    </div>


    <div class="row g-3">

        @forelse ($products as $product)

            <div class="col-12 col-sm-6 col-lg-3">

                <div class="product-card">

                    {{-- GAMBAR / HURUF --}}

                    <div class="product-image">

                        <div class="product-letter">

                            {{ strtoupper(substr($product->name, 0, 1)) }}

                        </div>

                    </div>


                    {{-- BODY --}}

                    <div class="product-body">

                        <div class="product-store">

                            {{ $product->store->name ?? '-' }}

                        </div>


                        <div class="product-name">

                            {{ $product->name }}

                        </div>


                        <div class="product-price mt-3">

                            Rp
                            {{ number_format($product->price, 0, ',', '.') }}

                        </div>


                        <div class="product-stock mt-1 mb-3">

                            Stok tersedia:
                            <strong class="text-success">
                                {{ $product->stock }}
                            </strong>

                        </div>


                        <a
                            href="{{ route('products.show', $product) }}"
                            class="btn btn-primary product-button"
                        >
                            Lihat Detail
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-light border text-center py-5">

                    Belum ada produk.

                </div>

            </div>

        @endforelse

    </div>


    {{-- PAGINATION --}}

    <div class="mt-4">

        {{ $products->links() }}

    </div>

</div>


@endsection