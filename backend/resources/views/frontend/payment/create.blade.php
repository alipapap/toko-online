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
                                class="form-check-input mt-1 payment-method-radio"
                                data-show-qr="true"
                                data-show-ewallet="false"
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
                                class="form-check-input mt-1 payment-method-radio"
                                data-show-qr="false"
                                data-show-ewallet="false"
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
                        class="d-block border rounded-4 p-4 mb-3 cursor-pointer hover:border-violet-500 transition"
                    >

                        <div class="d-flex align-items-start gap-3">

                            <input
                                type="radio"
                                name="method"
                                value="E-Wallet"
                                class="form-check-input mt-1 payment-method-radio"
                                data-show-qr="true"
                                data-show-ewallet="true"
                            >

                            <div class="w-100">

                                <div class="fw-bold">
                                    E-Wallet
                                </div>

                                <div class="text-secondary small mt-1">
                                    Bayar menggunakan dompet digital.
                                </div>


                                {{-- PILIHAN PROVIDER E-WALLET --}}
                                <div id="ewalletProviders" class="d-none mt-3">

                                    <div class="d-flex flex-wrap gap-2">

                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary btn-sm ewallet-provider-btn rounded-pill px-3"
                                            data-provider="GoPay"
                                        >
                                            GoPay
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary btn-sm ewallet-provider-btn rounded-pill px-3"
                                            data-provider="DANA"
                                        >
                                            DANA
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary btn-sm ewallet-provider-btn rounded-pill px-3"
                                            data-provider="OVO"
                                        >
                                            OVO
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary btn-sm ewallet-provider-btn rounded-pill px-3"
                                            data-provider="ShopeePay"
                                        >
                                            ShopeePay
                                        </button>

                                    </div>

                                    <div class="text-secondary small mt-2">
                                        Semua aplikasi di atas mendukung scan QR yang sama di bawah.
                                    </div>

                                </div>

                            </div>

                        </div>

                    </label>


                    {{-- Menyimpan e-wallet mana yang dipilih (opsional, tidak wajib) --}}
                    <input type="hidden" name="ewallet_provider" id="ewalletProviderInput" value="">


                    {{-- QR CODE (auto-generate per order, muncul untuk Transfer Bank / E-Wallet) --}}
                    <div id="qrisBox" class="text-center border rounded-4 p-4 mb-4 bg-light mt-2">

                        <div class="fw-semibold mb-3">
                            Scan QR untuk pesanan ini
                        </div>

                        <img
                            src="{{ route('order.qr', $order) }}"
                            alt="QR Pesanan #{{ $order->id }}"
                            style="max-width: 260px; width: 100%;"
                            class="img-fluid rounded-3 border bg-white p-2"
                        >

                        <div class="text-secondary small mt-3">
                            QR ini berisi info pesanan #{{ $order->id }} — hanya untuk keperluan demo, bukan QR pembayaran nyata.
                        </div>

                    </div>


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


<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('.payment-method-radio');
    const qrisBox = document.getElementById('qrisBox');
    const ewalletProviders = document.getElementById('ewalletProviders');
    const providerButtons = document.querySelectorAll('.ewallet-provider-btn');
    const providerInput = document.getElementById('ewalletProviderInput');

    function updateVisibility() {
        const checked = document.querySelector('.payment-method-radio:checked');
        const showQr = checked && checked.dataset.showQr === 'true';
        const showEwallet = checked && checked.dataset.showEwallet === 'true';

        qrisBox.style.display = showQr ? 'block' : 'none';
        ewalletProviders.classList.toggle('d-none', !showEwallet);

        if (!showEwallet) {
            providerInput.value = '';
            providerButtons.forEach(function (btn) {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-secondary');
            });
        }
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', updateVisibility);
    });

    providerButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            providerInput.value = btn.dataset.provider;

            providerButtons.forEach(function (b) {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-secondary');
            });

            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-primary');
        });
    });

    updateVisibility();
});
</script>

@endsection