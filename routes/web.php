<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePasswordResetController;
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

// =========================
// Dashboard
// =========================

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Todas as URLs do usuário
Route::get('/dashboard/urls', [DashboardController::class, 'urls'])
    ->middleware('auth')
    ->name('dashboard.urls');

// Métricas da URL
Route::get('/dashboard/{shortCode}/metrics', [DashboardController::class, 'metrics'])
    ->middleware('auth')
    ->name('dashboard.metrics');

// Editar uma URL
Route::get('/urls/{shortCode}/edit', [ShortUrlController::class, 'edit'])
    ->middleware('auth')
    ->name('dashboard.edit');

Route::put('/urls/{shortCode}', [ShortUrlController::class, 'update'])
    ->middleware('auth')
    ->name('dashboard.update');

// Excluir URL encurtada
Route::delete('/dashboard/{shortCode}', [DashboardController::class, 'destroy'])
    ->middleware('auth')
    ->name('dashboard.destroy');

// =========================
// Perfil
// =========================

Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth')
    ->name('profile');

Route::get('/profile/edit', [ProfileController::class, 'edit'])
    ->middleware('auth')
    ->name('profile.edit');

Route::put('/profile', [ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('profile.update');

Route::get('/profile/delete', [ProfileController::class, 'delete'])
    ->middleware('auth')
    ->name('profile.delete');

Route::delete('/profile', [ProfileController::class, 'destroy'])
    ->middleware('auth')
    ->name('profile.destroy');

Route::get('/profile/password', [ProfilePasswordResetController::class, 'create'])
    ->middleware('auth')
    ->name('profile.password.edit');

Route::post('/profile/password/code', [ProfilePasswordResetController::class, 'sendCode'])
    ->middleware('auth')
    ->name('profile.password.send-code');

Route::get('/profile/password/confirm', [ProfilePasswordResetController::class, 'confirm'])
    ->middleware('auth')
    ->name('profile.password.confirm');

Route::post('/profile/password/confirm', [ProfilePasswordResetController::class, 'verify'])
    ->middleware('auth')
    ->name('profile.password.verify');

// =========================
// Encurtar URL
// =========================

Route::post('/shorten', [ShortUrlController::class, 'store']);

// =========================
// Redirecionamento de URL curta
// =========================

Route::get('/{shortCode}', RedirectController::class)
    ->where('shortCode', '[A-Za-z0-9_-]+');
