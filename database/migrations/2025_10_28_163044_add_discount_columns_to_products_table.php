<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('price');
            $table->datetime('discount_start_date')->nullable()->after('discount_percentage');
            $table->datetime('discount_end_date')->nullable()->after('discount_start_date');
            $table->decimal('original_price', 8, 2)->nullable()->after('discount_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'discount_percentage',
                'discount_start_date', 
                'discount_end_date',
                'original_price'
            ]);
        });
    }
};
