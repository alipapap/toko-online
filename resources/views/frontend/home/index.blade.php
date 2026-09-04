{{-- resources/views/frontend/home/index.blade.php --}}

@extends('layouts.app')

@section('content')

<style>
    .home-page {
        background: #f8f9ff;
        min-height: 100vh;
        color: #172033;
    }

    .home-container {
        width: min(1180px, calc(100% - 40px));
        margin: 0 auto;
    }

    /* =========================
       HERO
    ========================= */
    .hero-shopping {
        position: relative;
        overflow: hidden;
        margin-top: 28px;
        min-height: 430px;
        border-radius: 32px;
        background:
            radial-gradient(circle at 90% 15%, rgba(255,255,255,.22) 0 70px, transparent 71px),
            radial-gradient(circle at 75% 90%, rgba(255,255,255,.12) 0 120px, transparent 121px),
            linear-gradient(135deg, #5b21f5 0%, #7c3aed 45%, #9333ea 100%);
        box-shadow: 0 25px 60px rgba(91, 33, 245, .22);
    }

    .hero-shopping::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        border: 45px solid rgba(255,255,255,.08);
        right: -80px;
        top: 110px;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: 1.1fr .9fr;
        align-items: center;
        min-height: 430px;
        padding: 55px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 15px;
        border-radius: 999px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.25);
        color: white;
        font-size: 13px;
        font-weight: 700;
        backdrop-filter: blur(10px);
    }

    .hero-title {
        margin: 18px 0 14px;
        max-width: 620px;
        color: white;
        font-size: clamp(38px, 5vw, 64px);
        line-height: 1.05;
        font-weight: 900;
        letter-spacing: -2px;
    }

    .hero-title span {
        color: #fde68a;
    }

    .hero-description {
        max-width: 520px;
        color: rgba(255,255,255,.84);
        font-size: 16px;
        line-height: 1.7;
    }

    .hero-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    .btn-shopping {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 14px 22px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 800;
        transition: .2s ease;
    }

    .btn-shopping:hover {
        transform: translateY(-2px);
    }

    .btn-primary-shopping {
        color: #5b21f5;
        background: white;
        box-shadow: 0 10px 25px rgba(0,0,0,.12);
    }

    .btn-outline-shopping {
        color: white;
        border: 1px solid rgba(255,255,255,.35);
        background: rgba(255,255,255,.08);
    }

    /* Shopping illustration */
    .hero-visual {
        position: relative;
        height: 330px;
    }

    .shopping-circle {
        position: absolute;
        width: 280px;
        height: 280px;
        right: 25px;
        top: 20px;
        border-radius: 50%;
        background: rgba(255,255,255,.10);
    }

    .shopping-bag {
        position: absolute;
        right: 65px;
        top: 45px;
        width: 190px;
        height: 205px;
        border-radius: 20px 20px 30px 30px;
        background: white;
        box-shadow: 0 30px 50px rgba(35, 16, 100, .25);
        transform: rotate(5deg);
    }

    .shopping-bag::before {
        content: "";
        position: absolute;
        width: 85px;
        height: 65px;
        left: 52px;
        top: -42px;
        border: 12px solid white;
        border-bottom: 0;
        border-radius: 55px 55px 0 0;
    }

    .bag-logo {
        position: absolute;
        left: 50%;
        top: 65px;
        transform: translateX(-50%);
        width: 65px;
        height: 65px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #7c3aed;
        color: white;
        font-size: 27px;
        font-weight: 900;
    }

    .bag-text {
        position: absolute;
        width: 100%;
        text-align: center;
        top: 140px;
        font-size: 14px;
        font-weight: 800;
        color: #5b21f5;
    }

    .floating-card {
        position: absolute;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0,0,0,.15);
        font-size: 12px;
        font-weight: 800;
    }

    .floating-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ede9fe;
        font-size: 20px;
    }

    .floating-one {
        left: 0;
        top: 45px;
    }

    .floating-two {
        right: 0;
        bottom: 30px;
    }

    /* =========================
       QUICK FEATURES
    ========================= */
    .features {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-top: 25px;
    }

    .feature {
        padding: 22px;
        border: 1px solid #e7e9f2;
        background: white;
        border-radius: 20px;
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .feature-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1edff;
        font-size: 22px;
    }

    .feature h3 {
        margin: 0 0 4px;
        font-size: 15px;
    }

    .feature p {
        margin: 0;
        color: #7b8191;
        font-size: 12px;
    }

    /* =========================
       SECTION
    ========================= */
    .home-section {
        margin-top: 60px;
    }

    .section-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .section-heading h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 900;
    }

    .section-heading p {
        margin: 6px 0 0;
        color: #7b8191;
        font-size: 13px;
    }

    .section-link {
        color: #6d28d9;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
    }

    /* =========================
       STORE CARDS
    ========================= */
    .store-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .store-card {
        position: relative;
        padding: 22px;
        border: 1px solid #e7e9f2;
        border-radius: 20px;
        background: white;
        text-decoration: none;
        color: inherit;
        transition: .2s ease;
    }

    .store-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(30, 41, 59, .09);
    }

    .store-avatar {
        width: 55px;
        height: 55px;
        border-radius: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        color: #6d28d9;
        font-size: 21px;
        font-weight: 900;
        margin-bottom: 16px;
    }

    .store-card h3 {
        margin: 0;
        font-size: 15px;
    }

    .store-card p {
        margin: 6px 0 0;
        color: #8a90a0;
        font-size: 12px;
    }

    /* =========================
       PROMO
    ========================= */
    .promo-banner {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        padding: 35px 40px;
        border-radius: 26px;
        background: #111827;
        color: white;
    }

    .promo-banner::after {
        content: "SHOP";
        position: absolute;
        right: 90px;
        bottom: -35px;
        font-size: 130px;
        font-weight: 900;
        color: rgba(255,255,255,.035);
        pointer-events: none;
    }

    .promo-label {
        color: #c4b5fd;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .promo-banner h2 {
        margin: 8px 0;
        font-size: 30px;
        font-weight: 900;
    }

    .promo-banner p {
        margin: 0;
        max-width: 600px;
        color: #cbd5e1;
        font-size: 13px;
    }

    /* =========================
       PRODUCT PREVIEW
    ========================= */
    .product-preview {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .mini-product {
        overflow: hidden;
        border: 1px solid #e7e9f2;
        border-radius: 20px;
        background: white;
    }

    .mini-product-image {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at 20% 20%, #ddd6fe 0 35px, transparent 36px),
            radial-gradient(circle at 85% 80%, #ede9fe 0 50px, transparent 51px),
            #f7f5ff;
        color: #7c3aed;
        font-size: 35px;
        font-weight: 900;
    }

    .mini-product-body {
        padding: 17px;
    }

    .mini-product-body small {
        color: #9ca3af;
    }

    .mini-product-body h3 {
        margin: 5px 0;
        font-size: 14px;
    }

    .mini-product-price {
        color: #2563eb;
        font-weight: 900;
        font-size: 14px;
    }

    /* =========================
       RESPONSIVE
    ========================= */
    @media (max-width: 850px) {
        .hero-content {
            grid-template-columns: 1fr;
            padding: 35px;
        }

        .hero-visual {
            display: none;
        }

        .features {
            grid-template-columns: 1fr;
        }

        .store-grid,
        .product-preview {
            grid-template-columns: repeat(2, 1fr);
        }

        .promo-banner {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    @media (max-width: 550px) {
        .home-container {
            width: min(100% - 24px, 1180px);
        }

        .hero-shopping {
            border-radius: 24px;
        }

        .hero-content {
            min-height: 400px;
            padding: 28px;
        }

        .hero-title {
            font-size: 39px;
        }

        .store-grid,
        .product-preview {
            grid-template-columns: 1fr;
        }

        .section-heading {
            align-items: start;
            gap: 10px;
            flex-direction: column;
        }
    }
</style>


<div class="home-page">

    <div class="home-container">

        {{-- =====================================================
             HERO UTAMA
        ====================================================== --}}
        <section class="hero-shopping">

            <div class="hero-content">

                <div>
                    <div class="hero-badge">
                        🛍️ Belanja lebih mudah di TokoKita
                    </div>

                    <h1 class="hero-title">
                        Temukan barang yang
                        <span>kamu suka.</span>
                    </h1>

                    <p class="hero-description">
                        Jelajahi berbagai produk pilihan dari toko-toko
                        terpercaya. Cari, pilih, dan belanja semuanya
                        dalam satu tempat.
                    </p>

                    <div class="hero-buttons">

                        <a href="{{ route('frontend.products.index') }}"
                           class="btn-shopping btn-primary-shopping">
                            🛒 Mulai Belanja
                        </a>

                        <a href="#toko"
                           class="btn-shopping btn-outline-shopping">
                            🏪 Jelajahi Toko
                        </a>

                    </div>
                </div>


                {{-- Ilustrasi shopping --}}
                <div class="hero-visual">

                    <div class="shopping-circle"></div>

                    <div class="floating-card floating-one">
                        <div class="floating-card-icon">🎁</div>
                        <div>
                            Banyak pilihan
                            <br>
                            <span style="color:#7c3aed;">untuk kamu</span>
                        </div>
                    </div>

                    <div class="shopping-bag">
                        <div class="bag-logo">TK</div>
                        <div class="bag-text">TOKOKITA</div>
                    </div>

                    <div class="floating-card floating-two">
                        <div class="floating-card-icon">⚡</div>
                        <div>
                            Belanja cepat
                            <br>
                            <span style="color:#7c3aed;">dan praktis</span>
                        </div>
                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             FEATURES
        ====================================================== --}}
        <section class="features">

            <div class="feature">
                <div class="feature-icon">🛍️</div>
                <div>
                    <h3>Belanja Praktis</h3>
                    <p>Pilih produk tanpa ribet.</p>
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon">🏪</div>
                <div>
                    <h3>Banyak Toko</h3>
                    <p>Temukan berbagai penjual.</p>
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon">📦</div>
                <div>
                    <h3>Pesanan Terorganisir</h3>
                    <p>Pantau pesanan dengan mudah.</p>
                </div>
            </div>

        </section>


        {{-- =====================================================
             TOKO PILIHAN
        ====================================================== --}}
        <section class="home-section" id="toko">

            <div class="section-heading">

                <div>
                    <h2>🏪 Temukan Toko</h2>
                    <p>
                        Jelajahi toko yang tersedia di TokoKita.
                    </p>
                </div>

            </div>


            @if(isset($stores) && $stores->count())

                <div class="store-grid">

                    @foreach($stores as $store)

                        <a href="{{ route('frontend.products.index', ['store_id' => $store->id]) }}"
                        class="store-card">

                            <div class="store-avatar">
                                {{ strtoupper(substr($store->name, 0, 1)) }}
                            </div>

                            <h3>
                                {{ $store->name }}
                            </h3>

                            <p>
                                {{ $store->address ?? 'Toko pilihan TokoKita' }}
                            </p>

                        </a>

                    @endforeach

                </div>

            @else

                <div class="feature">
                    <div class="feature-icon">🏪</div>
                    <div>
                        <h3>Belum ada toko</h3>
                        <p>Toko akan muncul di sini.</p>
                    </div>
                </div>

            @endif

        </section>


        {{-- =====================================================
             PROMO / SHOPPING BANNER
        ====================================================== --}}
        <section class="home-section">

            <div class="promo-banner">

                <div>

                    <div class="promo-label">
                        ✨ Saatnya Belanja
                    </div>

                    <h2>
                        Satu tempat untuk banyak kebutuhan.
                    </h2>

                    <p>
                        Tidak perlu berpindah-pindah. Temukan produk
                        dari berbagai toko dan pilih yang paling cocok
                        untukmu.
                    </p>

                </div>

                <div>

                    <a href="{{ route('frontend.products.index') }}"
                       class="btn-shopping btn-primary-shopping">
                        Lihat Semua Produk →
                    </a>

                </div>

            </div>

        </section>


        {{-- =====================================================
             PRODUK PILIHAN
             HANYA PREVIEW, BUKAN FOKUS UTAMA HOME
        ====================================================== --}}
        @if(isset($products) && $products->count())

            <section class="home-section">

                <div class="section-heading">

                    <div>
                        <h2>✨ Sedang Banyak Dilihat</h2>
                        <p>
                            Beberapa produk pilihan dari TokoKita.
                        </p>
                    </div>

                    <a href="{{ route('frontend.products.index') }}"
                       class="section-link">
                        Lihat Semuaa
                    </a>

                </div>


                <div class="product-preview">

                    @foreach($products->take(4) as $product)

                        <div class="mini-product">

                            <div class="mini-product-image">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>

                            <div class="mini-product-body">

                                <small>
                                    {{ $product->store->name ?? 'TokoKita' }}
                                </small>

                                <h3>
                                    {{ $product->name }}
                                </h3>

                                <div class="mini-product-price">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        @endif


        {{-- =====================================================
             FINAL CTA
        ====================================================== --}}
        <section class="home-section" style="padding-bottom:70px;">

            <div class="promo-banner"
                 style="background:linear-gradient(135deg,#7c3aed,#4f46e5);">

                <div>

                    <div class="promo-label"
                         style="color:#ddd6fe;">
                        TOKOKITA
                    </div>

                    <h2>
                        Sudah siap mulai belanja?
                    </h2>

                    <p>
                        Temukan produk favoritmu sekarang.
                    </p>

                </div>

                <div>

                    <a href="{{ route('frontend.products.index') }}"
                       class="btn-shopping btn-primary-shopping">
                        🛒 Belanja Sekarang 🛒🛒
                    </a>

                </div>

            </div>

        </section>

    </div>

</div>

@endsection