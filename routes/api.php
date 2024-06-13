<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\AuthController;


Route::post('/email/resend', [AuthController::class, 'resend'])->name('verification.resend');   
Route::get('/email/verify/{id}', [AuthController::class, 'verify'])->name('verification.verify');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/upload-image', [ProductController::class, 'uploadImage']);
Route::get('/get-image/{publicId}', [ProductController::class, 'getImage']);
Route::post('/product', [ProductController::class, 'store']);
Route::get('/product/show', [ProductController::class, 'index']);

Route::post('/login', [AuthController::class, 'login'])->middleware('verified');
Route::post('password/email', [AuthController::class, 'forgot']);
Route::post('password/reset', [AuthController::class, 'reset']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
});

Route::middleware('auth:sanctum','isAdmin')->group(function () { 
});