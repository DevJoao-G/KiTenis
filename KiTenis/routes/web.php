<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Home');
})->name('Home');


Route::get('/account', function () {
    return view('auth.Account');
})->name('Account');
