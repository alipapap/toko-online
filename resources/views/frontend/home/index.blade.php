<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TokoKita - Katalog Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --tk-primary: #6d28d9;
            --tk-primary-dark: #4c1d95;
        }
        body {
            background-color: #f8f9fc;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--tk-primary) !important;
        }
        .hero-banner {
            background: linear-gradient(135deg, #7c3aed 0%, #4c1d95 100%);
            border-radius: 1rem;
            color: #fff;
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
        }
        .hero-badge {
            background: rgba(255,255,255,0.15);
            border-radius: 999px;
            padding: 0.25rem 0.9rem;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 0.75rem;
        }
        .hero-logo-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.4rem;
            text-align: center;
            line-height: 1.1;
        }
        .btn-tk-light {
            background: #fff;
            color: var(--tk-primary);
            font-weight: 600;
            border: none;
        }
        .btn-tk-light:hover {
            background: #f1eafe;
            color: var(--tk-primary-dark);
        }
        .search-card {
            border: 1px solid #e9e9f3;
            border-radius: 0.75rem;
        }
        .product-card {
            border: 1px solid #ececf5;
            border-radius: 0.75rem;
            transition: box-shadow .15s ease, transform .15s ease;
        }
        .product-card:hover {
            box-shadow: 0 .5rem 1rem rgba(76, 29, 149, .08);
            transform: translateY(-2px);
        }
        .product-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ede9fe;
            color: var(--tk-primary);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-price {
            color: var(--tk-primary);
            font-weight: 700;
        }
        .badge-category {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #8a8aa3;
            letter-spacing: .04em;
        }
        footer {
            background: #16122a;
            color: #cfc9e6;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">TokoKita</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') ?? '#' }}">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') ?? '#' }}">Pesanan</a></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('cart.index') ?? '#' }}" class="btn btn-sm" style="background:var(--tk-primary);color:#fff;">Keranjang</a>
            <span class="text-muted small">{{ auth()->user()->name ?? 'Tamu' }}</span>
            <form method="POST" action="{{ route('logout') ?? '#' }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container my-4">

    {{-- HERO --}}
    <div class="hero-banner d-flex justify-content-between align-items-center flex-wrap">
        <div style="max-width: 640px;">
            <span class="hero-badge">Belanja lebih mudah</span>
            <h1 class="fw-bold mb-2">Temukan Produk Favoritmu</h1>
            <p class="mb-3" style="opacity:.9;">Temukan berbagai produk pilihan dari toko-toko yang tersedia di TokoKita.</p>
            <a href="#produk-pilihan" class="btn btn-tk-light rounded-pill px-4">Mulai Belanja</a>
        </div>
        <div class="hero-logo-circle d-none d-md-flex">TK<br><span style="font-weight:400;font-size:.65rem;">TokoKita</span></div>
    </div>

    {{-- SEARCH --}}
    <div class="search-card bg-white shadow-sm p-4 mt-4">
        <h5 class="fw-bold mb-1">Cari Produk</h5>
        <p class="text-muted small mb-3">Gunakan pencarian atau pilih toko untuk menemukan produk.</p>

        <form method="GET" action="{{ route('products.search') ?? '#' }}" class="row g-2 align-items-end">
            <div class="col-md-7">
                <label class="form-label small fw-semibold">Nama Produk</label>
                <input type="text" name="q" class="form-control" placeholder="Cari produk..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Toko</label>
                <select name="store_id" class="form-select">
                    <option value="">Semua Toko</option>
                    @foreach($stores ?? [] as $store)
                        <option value="{{ $store->id }}" @selected(request('store_id') == $store->id)>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" style="background:var(--tk-primary);border-color:var(--tk-primary);">Cari</button>
            </div>
        </form>
    </div>

    {{-- PRODUK PILIHAN --}}
    <div id="produk-pilihan" class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-0">Produk Pilihan</h5>
                <p class="text-muted small mb-0">Pilihan produk yang tersedia saat ini.</p>
            </div>
            <span class="badge rounded-pill" style="background:#ede9fe;color:var(--tk-primary);">
                {{ ($products ?? collect())->count() }} Produk
            </span>
        </div>

        <div class="row g-3">
            @forelse(($products ?? []) as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card bg-white p-3 h-100 d-flex flex-column">
                        <div class="product-avatar mb-2">
                            {{ strtoupper(substr($product->name, 0, 1)) }}
                        </div>

                        <div class="badge-category mb-1">
                            {{ $product->category ?? ($product->store->name ?? '') }}
                        </div>
                        <div class="fw-semibold mb-1">{{ $product->name }}</div>

                        <div class="product-price mb-1">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        <div class="text-muted small mb-3">
                            Stok tersedia: {{ $product->stock }}
                        </div>

                        <a href="{{ route('products.show', $product->id) ?? '#' }}"
                           class="btn btn-sm mt-auto"
                           style="background:var(--tk-primary);color:#fff;">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                {{-- Data contoh (sample) jika belum ada data produk dari controller --}}
                @php
                    $sampleProducts = [
                        ['name' => 'cantik itu luka', 'category' => 'book', 'price' => 150000, 'stock' => 20],
                        ['name' => 'baju koko', 'category' => 'thrift', 'price' => 50000, 'stock' => 34],
                        ['name' => 'Novel Bandung Menjelang Pagi', 'category' => 'book', 'price' => 99000, 'stock' => 27],
                        ['name' => 'Kaos', 'category' => 'thrift', 'price' => 50000, 'stock' => 95],
                    ];
                @endphp
                @foreach($sampleProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card bg-white p-3 h-100 d-flex flex-column">
                            <div class="product-avatar mb-2">{{ strtoupper(substr($product['name'], 0, 1)) }}</div>
                            <div class="badge-category mb-1">{{ $product['category'] }}</div>
                            <div class="fw-semibold mb-1">{{ $product['name'] }}</div>
                            <div class="product-price mb-1">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>
                            <div class="text-muted small mb-3">Stok tersedia: {{ $product['stock'] }}</div>
                            <a href="#" class="btn btn-sm mt-auto" style="background:var(--tk-primary);color:#fff;">Lihat Detail</a>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </div>
</div>

<footer class="mt-5 py-4">
    <div class="container d-flex justify-content-between flex-wrap">
        <div>
            <div class="fw-bold">TokoKita</div>
            <div class="small">Belanja mudah, nyaman, dan aman.</div>
        </div>
        <div class="small align-self-end">&copy; {{ date('Y') }} TokoKita</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>