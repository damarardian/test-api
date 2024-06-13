<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/emailverified', function () {
    return view('emailverified');
});

Route::view('forgot_password', 'reset_password')->name('password.reset');
