<?php

use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\NewsletterController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Site\FavoriteController;
use Illuminate\Support\Facades\Route;


// ========================================
// ROTAS PÚBLICAS
// ========================================

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('produtos')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');

    // ✅ NOVAS PÁGINAS (para o navbar)
    Route::get('/masculino', [ProductController::class, 'masculino'])->name('masculino');
    Route::get('/feminino', [ProductController::class, 'feminino'])->name('feminino');
    Route::get('/ofertas', [ProductController::class, 'ofertas'])->name('ofertas');

    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
});

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

// ========================================
// ROTAS AUTENTICADAS
// ========================================

Route::middleware('auth')->group(function () {

    // Carrinho
    Route::prefix('carrinho')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/adicionar', [CartController::class, 'add'])->name('add');
        Route::delete('/remover/{id}', [CartController::class, 'remove'])->name('remove');
    });

    // Conta do Usuário
    Route::get('/conta', [AccountController::class, 'index'])->name('account');

    // Pedidos
    Route::prefix('pedidos')->name('orders.')->group(function () {
        Route::get('/', [AccountController::class, 'orders'])->name('index');
        Route::get('/{order}', [AccountController::class, 'showOrder'])->name('show');
    });

        // Favoritos
    Route::post('/favoritos/{product}/toggle', [FavoriteController::class, 'toggle'])
        ->name('favorites.toggle');


});

// ========================================
// ROTAS DE AUTENTICAÇÃO
// ========================================

require __DIR__.'/auth.php';
