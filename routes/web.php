<?php

use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sobre', function () {
    return view('about');
})->name('about');

// Verificar E-mail
Route::get('/verificar-email', [EmailVerificationController::class, 'create'])
    ->name('verification.create');

Route::post(
    '/verificar-email',
    [EmailVerificationController::class, 'store']
)->name('verification.store');

Route::post(
    '/verificar-email/reenviar',
    [EmailVerificationController::class, 'resend']
)->name('verification.resend');


// Criar conta
Route::get('/register', [RegisterController::class, 'create'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

Route::post('/shorten', [ShortUrlController::class, 'store']);

Route::get('/{shortCode}', RedirectController::class)
    ->where('shortCode', '[A-Za-z0-9]+');

