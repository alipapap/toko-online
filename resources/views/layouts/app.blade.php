<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        TokoKita - @yield('title', 'Beranda')
    </title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f8fafc;
            color: #1e293b;
        }

        /* =========================
           NAVBAR
        ========================= */

        .tokokita-navbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
        }

        .tokokita-logo {
            color: #6c5ce7 !important;
            font-size: 24px;
            font-weight: 800;
            text-decoration: none;
        }

        .navbar-nav .nav-link {
            color: #374151;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 15px !important;
            border-radius: 30px;
        }

        .navbar-nav .nav-link:hover {
            color: #6c5ce7;
            background: #f3f0ff;
        }

        .cart-button {
            background: #6c5ce7;
            color: #fff !important;
            border-radius: 50px;
            padding: 9px 18px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .cart-button:hover {
            background: #5848d6;
            color: #fff !important;
        }

        .cart-badge {
            background: #fff;
            color: #6c5ce7;
            font-size: 11px;
            font-weight: 700;
            min-width: 21px;
            height: 21px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
        }

        .user-name {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            padding: 8px 10px;
        }

        .main-content {
            min-height: calc(100vh - 220px);
        }

        /* =========================
           FOOTER
        ========================= */

        .tokokita-footer {
            background: #0f172a;
            color: white;
        }

        .footer-muted {
            color: #94a3b8;
        }

        @media (max-width: 991px) {

            .navbar-nav {
                margin-top: 15px;
            }

            .navbar-right {
                margin-top: 10px;
                padding-bottom: 10px;
            }

            .cart-button {
                justify-content: center;
            }

        }

    </style>

    @stack('styles')

</head>


<body>


    {{-- =====================================================
         NAVBAR
    ====================================================== --}}

    <nav class="navbar navbar-expand-lg tokokita-navbar sticky-top">

        <div class="container py-2">

            {{-- LOGO --}}
            <a
                href="{{ route('home') }}"
                class="tokokita-logo"
            >
                 TokoKita
            </a>


            {{-- MOBILE --}}
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu"
                aria-controls="navbarMenu"
                aria-expanded="false"
            >
                <span class="navbar-toggler-icon"></span>
            </button>


            <div
                class="collapse navbar-collapse"
                id="navbarMenu"
            >

                {{-- MENU KIRI --}}
                <ul class="navbar-nav ms-lg-4 me-auto">

                    <li class="nav-item">

                        <a
                            href="{{ route('home') }}"
                            class="nav-link"
                        >
                            Produk
                        </a>

                    </li>

                    @auth

                        <li class="nav-item">

                            <a
                                href="{{ route('orders.index') }}"
                                class="nav-link"
                            >
                                Pesanan
                            </a>

                        </li>

                    @endauth

                </ul>


                {{-- MENU KANAN --}}
                <div class="navbar-right d-flex align-items-center gap-2">

                    @auth

                        @php

                            $cart = session('cart', []);

                            $cartCount = 0;

                            if (is_array($cart)) {

                                foreach ($cart as $item) {

                                    if (is_array($item)) {

                                        $cartCount += (int) (
                                            $item['quantity'] ?? 0
                                        );

                                    } else {

                                        $cartCount += (int) $item;

                                    }

                                }

                            }

                        @endphp


                        {{-- KERANJANG --}}
                        <a
                            href="{{ route('cart.index') }}"
                            class="cart-button"
                        >

                            Keranjang

                            @if ($cartCount > 0)

                                <span class="cart-badge">
                                    {{ $cartCount }}
                                </span>

                            @endif

                        </a>


                        {{-- USER --}}
                        <span class="user-name">
                            {{ auth()->user()->name }}
                        </span>


                        {{-- LOGOUT --}}
                        <form
                            action="{{ route('logout') }}"
                            method="POST"
                            class="m-0"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-outline-danger rounded-pill px-3"
                            >
                                Logout
                            </button>

                        </form>


                    @else

                        {{-- LOGIN --}}
                        <a
                            href="{{ route('login') }}"
                            class="btn btn-outline-secondary rounded-pill px-4"
                        >
                            Login
                        </a>


                        {{-- DAFTAR --}}
                        <a
                            href="{{ route('register') }}"
                            class="btn btn-primary rounded-pill px-4"
                        >
                            Daftar
                        </a>

                    @endauth

                </div>

            </div>

        </div>

    </nav>


    {{-- =====================================================
         CONTENT
    ====================================================== --}}

    <main class="container py-4 main-content">

        @if (session('success'))

            <div class="alert alert-success rounded-4 border-0 shadow-sm">
                ✓ {{ session('success') }}
            </div>

        @endif


        @if (session('error'))

            <div class="alert alert-danger rounded-4 border-0 shadow-sm">
                {{ session('error') }}
            </div>

        @endif


        @if ($errors->any())

            <div class="alert alert-danger rounded-4 border-0 shadow-sm">

                <strong>
                    Ada beberapa kesalahan:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        @yield('content')

    </main>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <footer class="tokokita-footer mt-5">

        <div class="container py-5">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h5 class="fw-bold mb-2">
                         TokoKita
                    </h5>

                    <p class="footer-muted mb-0">
                        Belanja mudah, nyaman, dan aman.
                    </p>

                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">

                    <p class="footer-muted mb-0">
                        © {{ date('Y') }} TokoKita
                    </p>

                </div>

            </div>

        </div>

    </footer>


    {{-- Bootstrap JS --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

    @stack('scripts')

</body>

</html>