<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Product;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Activate scheduled discounts - runs daily at midnight
Schedule::call(function () {
    $now = now();
    
    // Find products with future discounts that should be activated today
    $products = Product::whereNotNull('discount_percentage')
        ->whereNotNull('discount_start_date')
        ->whereNotNull('original_price')
        ->whereDate('discount_start_date', '<=', $now)
        ->where(function ($query) use ($now) {
            $query->whereNull('discount_end_date')
                  ->orWhereDate('discount_end_date', '>=', $now);
        })
        ->get();

    $activatedCount = 0;

    foreach ($products as $product) {
        // Check if price hasn't been discounted yet (future discount)
        $expectedDiscountedPrice = floor($product->original_price * (1 - ($product->discount_percentage / 100)));
        
        if ($product->price != $expectedDiscountedPrice) {
            // Apply the discount now
            $product->price = $expectedDiscountedPrice;
            $product->save();
            $activatedCount++;
            
            \Log::info("Activated scheduled discount for product: {$product->id} ({$product->title})");
        }
    }
    
    if ($activatedCount > 0) {
        \Log::info("Activated {$activatedCount} scheduled discounts");
    }
})->daily()->name('activate-scheduled-discounts');

// Deactivate expired discounts - runs daily at midnight
Schedule::call(function () {
    $now = now();
    
    // Find products with expired discounts
    $products = Product::whereNotNull('discount_percentage')
        ->whereNotNull('discount_end_date')
        ->whereNotNull('original_price')
        ->whereDate('discount_end_date', '<', $now)
        ->get();

    $deactivatedCount = 0;

    foreach ($products as $product) {
        // Restore original price and clear discount
        $product->price = $product->original_price;
        $product->discount_percentage = null;
        $product->discount_start_date = null;
        $product->discount_end_date = null;
        $product->original_price = null;
        $product->save();
        $deactivatedCount++;
        
        \Log::info("Deactivated expired discount for product: {$product->id} ({$product->title})");
    }
    
    if ($deactivatedCount > 0) {
        \Log::info("Deactivated {$deactivatedCount} expired discounts");
    }
})->daily()->name('deactivate-expired-discounts');
