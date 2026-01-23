<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

// ========================================
// ROTAS ADMINISTRATIVAS (LEGADAS / OPCIONAIS)
// ATENÇÃO: NÃO use prefixo "admin", pois o Filament usa /admin
// ========================================

Route::middleware(['auth'])
    ->prefix('admin-app')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Produtos
        Route::resource('produtos', ProductController::class)->except(['show']);

        // Pedidos
        Route::prefix('pedidos')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        });

    });
