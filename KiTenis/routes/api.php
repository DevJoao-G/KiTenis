<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas de autenticação - sem middleware para /me (verifica manualmente)
Route::get('/me', [AuthController::class, 'me']);

// Logout precisa estar autenticado
Route::middleware('auth:web')->post('/logout', [AuthController::class, 'logout']);