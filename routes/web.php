<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Redirect root to shop
Route::get('/', function () {
    return redirect()->route('shop.index');
});

// Public shop routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin routes (protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/images', [ProductController::class, 'uploadImage'])->name('products.images.upload');
    Route::delete('images/{image}', [ProductController::class, 'deleteImage'])->name('images.delete');
    Route::patch('images/{image}/set-cover', [ProductController::class, 'setCoverImage'])->name('images.set-cover');
});
