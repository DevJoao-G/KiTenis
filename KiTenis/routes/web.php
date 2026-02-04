<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\CheckoutController;
use App\Http\Controllers\Site\FavoriteController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\NewsletterController;
use App\Http\Controllers\Site\ProductController;
use Illuminate\Support\Facades\Route;

// ========================================
// ROTAS PÚBLICAS
// ========================================

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('produtos')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');

    Route::get('/masculino', [ProductController::class, 'masculino'])->name('masculino');
    Route::get('/feminino', [ProductController::class, 'feminino'])->name('feminino');
    Route::get('/infantil', [ProductController::class, 'infantil'])->name('infantil');
    Route::get('/ofertas', [ProductController::class, 'ofertas'])->name('ofertas');

    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
});

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');


// ========================================
// CHECKOUT - ROTAS DE RETORNO (PÚBLICAS)
// ========================================
//
// IMPORTANTE: o Mercado Pago precisa conseguir voltar nessas URLs.
// Se elas estiverem dentro do auth, o usuário pode cair no login,
// e o retorno "success/failure/pending" quebra.
//
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
Route::get('/checkout/pending', [CheckoutController::class, 'pending'])->name('checkout.pending');


// ========================================
// ROTAS AUTENTICADAS
// ========================================

Route::middleware('auth')->group(function () {

    // Carrinho (session)
    Route::prefix('carrinho')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/adicionar', [CartController::class, 'add'])->name('add');
        Route::patch('/atualizar/{key}', [CartController::class, 'update'])->name('update');
        Route::delete('/remover/{key}', [CartController::class, 'remove'])->name('remove');
    });

    // Checkout (Mercado Pago - iniciar pagamento)
    Route::post('/checkout/mercadopago', [CheckoutController::class, 'mercadoPago'])
        ->name('checkout.mercadopago');

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

require __DIR__ . '/auth.php';
