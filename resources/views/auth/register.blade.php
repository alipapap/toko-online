@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center py-5">

    <div class="w-full max-w-md">

        <div class="text-center mb-4">

            <h1 class="fw-bold">
                Buat Akun
            </h1>

            <p class="text-secondary">
                Daftar untuk mulai berbelanja di TokoKita.
            </p>

        </div>


        <div class="bg-white rounded-4 shadow-sm border p-4 p-md-5">

            <form
                method="POST"
                action="{{ route('register') }}"
            >

                @csrf


                {{-- NAMA --}}
                <div class="mb-4">

                    <label
                        for="name"
                        class="form-label fw-semibold"
                    >
                        Nama Lengkap
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-control form-control-lg rounded-3"
                        value="{{ old('name') }}"
                        placeholder="Nama lengkap"
                        required
                        autofocus
                    >

                </div>


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


                {{-- KONFIRMASI PASSWORD --}}
                <div class="mb-4">

                    <label
                        for="password_confirmation"
                        class="form-label fw-semibold"
                    >
                        Konfirmasi Password
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control form-control-lg rounded-3"
                        placeholder="Ulangi password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-primary btn-lg w-100 rounded-pill"
                >
                    Buat Akun
                </button>

            </form>


            <div class="text-center mt-4 pt-3 border-top">

                <span class="text-secondary">
                    Sudah punya akun?
                </span>

                <a
                    href="{{ route('login') }}"
                    class="text-primary fw-semibold text-decoration-none"
                >
                    Login
                </a>

            </div>

        </div>

    </div>

</div>

@endsection