<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Redirect root to shop
Route::get('/', function () {
    return redirect()->route('shop.index');
});

// Public shop routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/filosofia', function () {
    return inertia('Shop/Philosophy');
})->name('shop.philosophy');
Route::get('/contatti', function () {
    return inertia('Shop/Contacts');
})->name('shop.contacts');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/contact', [ShopController::class, 'sendContactEmail'])->name('contact.send');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin routes (protected by auth, admin, and editor restrictions middleware)
Route::middleware(['auth', 'admin', 'editor.restrictions'])->prefix('admin')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/images', [ProductController::class, 'uploadImage'])->name('products.images.upload');
    Route::delete('images/{image}', [ProductController::class, 'deleteImage'])->name('images.delete');
    Route::patch('images/{image}/set-cover', [ProductController::class, 'setCoverImage'])->name('images.set-cover');
        Route::post('discounts/apply', [\App\Http\Controllers\Admin\DiscountController::class, 'applyDiscount'])->name('discounts.apply');
        Route::post('discounts/remove/{product}', [\App\Http\Controllers\Admin\DiscountController::class, 'removeDiscount'])->name('discounts.remove');
        Route::post('discounts/extend/{product}', [\App\Http\Controllers\Admin\DiscountController::class, 'extendDiscount'])->name('discounts.extend');
        Route::post('discounts/modify/{product}', [\App\Http\Controllers\Admin\DiscountController::class, 'modifyDiscount'])->name('discounts.modify');
    
    // Customer routes
    Route::resource('customers', CustomerController::class);
    Route::get('customers/merge/show', [CustomerController::class, 'showMerge'])->name('customers.merge.show');
    Route::post('customers/merge', [CustomerController::class, 'merge'])->name('customers.merge');
    Route::get('customers/export/csv', [CustomerController::class, 'export'])->name('customers.export');
    Route::post('inquiries/{inquiry}/update', [CustomerController::class, 'updateInquiry'])->name('inquiries.update');
});
