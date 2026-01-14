<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/account', function () {
    return view('auth.Account');
})->name('Account');
