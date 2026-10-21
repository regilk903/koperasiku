<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('anggota.index'));
    Route::get('/dashboard', fn () => redirect()->route('anggota.index'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Lihat data (anggota hanya melihat miliknya, difilter di controller)
Route::middleware(['auth', 'role:admin,pengurus,bendahara,anggota'])->group(function () {
    Route::get('anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
});

// Kelola anggota
Route::middleware(['auth', 'role:admin,pengurus'])->group(function () {
    Route::resource('anggota', AnggotaController::class)->except(['index', 'show']);
});

// Catat & verifikasi simpanan
Route::middleware(['auth', 'role:admin,bendahara'])->group(function () {
    Route::resource('simpanan', SimpananController::class)->only(['create', 'store']);
    Route::patch('simpanan/{simpanan}/verifikasi', [SimpananController::class, 'verifikasi'])->name('simpanan.verifikasi');
});

// Manajemen user
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
