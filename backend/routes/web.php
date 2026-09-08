<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Frontend
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/home', [HomeController::class, 'index'])
    ->name('home');

// Katalog semua produk
Route::get('/products', [ProductController::class, 'index'])
    ->name('frontend.products.index');

// Detail produk
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');

// Pencarian produk
Route::get('/produk/cari', [HomeController::class, 'index'])
    ->name('products.search');


/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| Cart, Checkout & Orders
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Cart
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/{product}', [CartController::class, 'add'])
        ->name('cart.add');

    Route::patch('/cart/{product}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/{product}', [CartController::class, 'remove'])
        ->name('cart.remove');


    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');


    // Orders
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');


    // Payment
    Route::get('/orders/{order}/payment', [PaymentController::class, 'create'])
        ->name('payment.create');

    Route::post('/orders/{order}/payment', [PaymentController::class, 'store'])
        ->name('payment.store');

    // QR Code pembayaran (auto-generate per order)
    Route::get('/orders/{order}/qr', [PaymentController::class, 'qrCode'])
        ->name('order.qr');
});


/*
|--------------------------------------------------------------------------
| Test
|--------------------------------------------------------------------------
*/

Route::get('/fresh', function () {
    return view('layouts.app');
});