@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center py-5">

    <div class="w-full max-w-md">

        <div class="text-center mb-4">

            <h1 class="fw-bold">
                Selamat Datang
            </h1>

            <p class="text-secondary">
                Login untuk melanjutkan ke TokoKita.
            </p>

        </div>


        <div class="bg-white rounded-4 shadow-sm border p-4 p-md-5">

            <form
                method="POST"
                action="{{ route('login') }}"
            >

                @csrf


                {{-- EMAIL --}}
                <div class="mb-4">

                    <label
                        for="email"
                        class="form-label fw-semibold"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control form-control-lg rounded-3"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        required
                        autofocus
                    >

                </div>


                {{-- PASSWORD --}}
                <div class="mb-4">

                    <label
                        for="password"
                        class="form-label fw-semibold"
                    >
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control form-control-lg rounded-3"
                        placeholder="Masukkan password"
                        required
                    >

                </div>


                {{-- REMEMBER --}}
                <div class="form-check mb-4">

                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="form-check-input"
                    >

                    <label
                        for="remember"
                        class="form-check-label text-secondary"
                    >
                        Ingat saya
                    </label>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary btn-lg w-100 rounded-pill"
                >
                    Login
                </button>

            </form>


            <div class="text-center mt-4 pt-3 border-top">

                <span class="text-secondary">
                    Belum punya akun?
                </span>

                <a
                    href="{{ route('register') }}"
                    class="text-primary fw-semibold text-decoration-none"
                >
                    Daftar sekarang
                </a>

            </div>

        </div>

    </div>

</div>

@endsection