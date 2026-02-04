<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Para evitar duplicar pedidos quando o usuário recarrega o /checkout/success
            $table->string('external_reference')->nullable()->unique()->after('user_id');

            // Ajuda na auditoria dentro do painel
            $table->string('preference_id')->nullable()->after('external_reference');
            $table->string('payment_method')->nullable()->after('preference_id');
            $table->timestamp('paid_at')->nullable()->after('payment_method');
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Variantes que já existem no carrinho
            $table->string('size', 20)->nullable()->after('quantity');
            $table->string('color', 50)->nullable()->after('size');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['size', 'color']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['external_reference', 'preference_id', 'payment_method', 'paid_at']);
        });
    }
};
