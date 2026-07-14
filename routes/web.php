<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BuahController;
use App\Http\Controllers\Admin\RestokController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Kasir\KasirController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Rute untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

// Rute untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rute netral yang mengarahkan ke dashboard sesuai role
    Route::get('/dashboard', function () {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kasir' => redirect()->route('kasir.dashboard'),
            default => redirect('/'),
        };
    })->name('dashboard');

    // Khusus admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/buah', [BuahController::class, 'index'])->name('buah.index');
        Route::post('/buah', [BuahController::class, 'store'])->name('buah.store');
        Route::post('/buah/{buah}/rusak', [BuahController::class, 'reportRusak'])->name('buah.rusak');
        Route::get('/buah/{buah}/edit', [BuahController::class, 'edit'])->name('buah.edit');
        Route::put('/buah/{buah}', [BuahController::class, 'update'])->name('buah.update');
        Route::delete('/buah/{buah}', [BuahController::class, 'destroy'])->name('buah.destroy');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/restok', [RestokController::class, 'index'])->name('restok.index');
        Route::post('/restok', [RestokController::class, 'store'])->name('restok.store');
    });

    // Khusus kasir
    Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');
        Route::post('/transaksi', [KasirController::class, 'storeTransaksi'])->name('transaksi.store');
        Route::get('/riwayat', [KasirController::class, 'riwayat'])->name('riwayat');
    });
});
