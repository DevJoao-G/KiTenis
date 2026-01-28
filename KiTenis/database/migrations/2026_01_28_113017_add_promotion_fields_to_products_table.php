<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('price');
            $table->dateTime('promotion_start')->nullable()->after('discount_percentage');
            $table->dateTime('promotion_end')->nullable()->after('promotion_start');
            $table->boolean('featured_in_carousel')->default(false)->after('active');

            $table->index(['featured_in_carousel', 'promotion_start', 'promotion_end'], 'products_promo_carousel_idx');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_promo_carousel_idx');

            $table->dropColumn([
                'discount_percentage',
                'promotion_start',
                'promotion_end',
                'featured_in_carousel',
            ]);
        });
    }
};
