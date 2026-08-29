<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/sobre', function () {
    return view('about');
})->name('about');

// Verificar E-mail
Route::get('/verificar-email', [EmailVerificationController::class, 'create'])
    ->name('verification.create');

Route::post('/verificar-email', [EmailVerificationController::class, 'store'])
    ->name('verification.store');

Route::post('/verificar-email/reenviar', [EmailVerificationController::class, 'resend'])
    ->name('verification.resend');

// Criar conta
Route::get('/register', [RegisterController::class, 'create'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

// Login
Route::get('/login', [LoginController::class, 'create'])
    ->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->name('login.store');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Encurtar URL
Route::post('/shorten', [ShortUrlController::class, 'store']);

// Redirecionamento de URL curta
Route::get('/{shortCode}', RedirectController::class)
    ->where('shortCode', '[A-Za-z0-9]+');
