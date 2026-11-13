<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman Utama / Login
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect('/admin');
        }

        return redirect()->route('portal');
    }

    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google SSO Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Quick Dev Login (Testing Lokal: Mendukung GET & POST)
Route::match(['get', 'post'], '/dev-login/{user}', [AuthController::class, 'devLogin'])->name('dev.login');

// Portal Siswa & Guru (Harus Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/portal', function () {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect('/admin');
        }

        return view('portal');
    })->name('portal');
});
