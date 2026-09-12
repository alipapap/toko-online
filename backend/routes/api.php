<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\OrdersController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ORDERS
    Route::get('/orders', [OrdersController::class, 'index']);
    Route::get('/orders/{order}', [OrdersController::class, 'show']);

    // CART
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/{product}', [CartController::class, 'add']);
    Route::patch('/cart/{product}', [CartController::class, 'update']);
    Route::delete('/cart/{product}', [CartController::class, 'remove']);

    // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'index']);
    Route::post('/checkout', [CheckoutController::class, 'store']);

    // PAYMENT
    Route::get('/payment/{order}', [PaymentController::class, 'show']);
    Route::post('/payment/{order}', [PaymentController::class, 'store']);
});

// QR code diakses via <img>
// Sengaja di luar auth:sanctum
Route::get('/payment/{order}/qr', [PaymentController::class, 'qrCode']);

// STORES
Route::get('/stores', [StoreController::class, 'index']);
Route::get('/stores/{id}', [StoreController::class, 'show']);

// PRODUCTS
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);