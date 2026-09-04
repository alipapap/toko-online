@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="mb-4">

            <a
                href="{{ route('orders.show', $order) }}"
                class="text-decoration-none text-secondary small"
            >
                ← Kembali ke pesanan
            </a>

            <h1 class="fw-bold mt-3 mb-1">
                Pembayaran
            </h1>

            <p class="text-secondary mb-0">
                Pesanan #{{ $order->id }}
            </p>

        </div>


        <div class="bg-white rounded-4 shadow-sm border overflow-hidden">

            {{-- TOTAL --}}
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 text-white p-4 p-md-5">

                <div class="small text-white/70">
                    Total tagihan
                </div>

                <div class="fs-1 fw-bold mt-2">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </div>

            </div>


            <div class="p-4 p-md-5">

                <form
                    method="POST"
                    action="{{ route('payment.store', $order) }}"
                >

                    @csrf


                    <h5 class="fw-bold mb-4">
                        Pilih Metode Pembayaran
                    </h5>


                    {{-- TRANSFER BANK --}}
                    <label
                        class="d-block border rounded-4 p-4 mb-3 cursor-pointer hover:border-violet-500 transition"
                    >

                        <div class="d-flex align-items-start gap-3">

                            <input
                                type="radio"
                                name="method"
                                value="Transfer Bank"
                                class="form-check-input mt-1"
                                checked
                            >

                            <div>

                                <div class="fw-bold">
                                    Transfer Bank
                                </div>

                                <div class="text-secondary small mt-1">
                                    Lakukan pembayaran melalui transfer bank.
                                </div>

                            </div>

                        </div>

                    </label>


                    {{-- COD --}}
                    <label
                        class="d-block border rounded-4 p-4 mb-3 cursor-pointer hover:border-violet-500 transition"
                    >

                        <div class="d-flex align-items-start gap-3">

                            <input
                                type="radio"
                                name="method"
                                value="COD"
                                class="form-check-input mt-1"
                            >

                            <div>

                                <div class="fw-bold">
                                    COD
                                </div>

                                <div class="text-secondary small mt-1">
                                    Bayar ketika pesanan diterima.
                                </div>

                            </div>

                        </div>

                    </label>


                    {{-- E-WALLET --}}
                    <label
                        class="d-block border rounded-4 p-4 mb-4 cursor-pointer hover:border-violet-500 transition"
                    >

                        <div class="d-flex align-items-start gap-3">

                            <input
                                type="radio"
                                name="method"
                                value="E-Wallet"
                                class="form-check-input mt-1"
                            >

                            <div>

                                <div class="fw-bold">
                                    E-Wallet
                                </div>

                                <div class="text-secondary small mt-1">
                                    Bayar menggunakan dompet digital.
                                </div>

                            </div>

                        </div>

                    </label>


                    <div class="border-top pt-4">

                        <div class="d-flex justify-content-between mb-4">

                            <span class="text-secondary">
                                Total pembayaran
                            </span>

                            <strong class="text-primary fs-5">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </strong>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100 rounded-pill py-3"
                        >
                            Bayar Sekarang
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection