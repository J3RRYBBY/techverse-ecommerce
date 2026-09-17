<?php

use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\RatingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'home'])->name('user#home');

Route::get('/product', [ProductController::class, 'productList'])->name('user#productList');

Route::get('/product/details/{id}', [ProductController::class, 'productDetails'])->name('user#productDetails');

Route::get('/category', [CategoryController::class, 'category'])->name('user#category');

Route::middleware(['auth', 'userMiddleware'])->group(function () {
    // Route::get('/shop', [ProductController::class, 'productList'])->name('user#productList');

    // Route::get('/product/details/{id}', [ProductController::class, 'productDetails'])->name(
    //     'user#productDetails',
    // );

    // review
    Route::post('/product/{product}/feedback', [RatingController::class, 'feedback'])->name('user#feedback');

    Route::get('/product/{product}/all-reviews', [RatingController::class, 'allReviews'])->name('user#allReviews');

    // profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('user#profile');

    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('user#updateProfile');

    // cart
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('user#addToCart');

    Route::get('/cart', [CartController::class, 'createCart'])->name('user#cart');

    Route::delete('/cart/remove/{variantId}', [CartController::class, 'removeCartItems'])->name('user#removeCartItems');

    // checkout
    Route::get('/checkout', [CheckoutController::class, 'createCheckout'])->name('user#checkout');

    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('user#placeOrder');

    // Route::get('/order-success/{order}', [CheckoutController::class, 'orderSuccess'])->name('user#orderSuccess');

    // order
    Route::get('/orders', [OrderController::class, 'myOrder'])->name('user#myOrder');

    Route::get('/orders/{order}', [OrderController::class, 'orderDetails'])->name('user#orderDetails');
});
