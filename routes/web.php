
<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AngsuranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PinjamanController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

    // ===== DASHBOARD =====
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ===== ANGGOTA =====
    Route::resource('anggotas', AnggotaController::class);

    // ===== PINJAMAN =====
    Route::resource('pinjaman', PinjamanController::class);

    // Proses persetujuan
    Route::post(
        '/pinjaman/{pinjaman}/approve',
        [PinjamanController::class, 'approve']
    )->name('pinjaman.approve');

    // Proses penolakan
    Route::post(
        '/pinjaman/{pinjaman}/reject',
        [PinjamanController::class, 'reject']
    )->name('pinjaman.reject');

    // Proses pencairan
    Route::post(
        '/pinjaman/{pinjaman}/disburse',
        [PinjamanController::class, 'disburse']
    )->name('pinjaman.disburse');

    // Pembatalan
    Route::post(
        '/pinjaman/{pinjaman}/cancel',
        [PinjamanController::class, 'cancel']
    )->name('pinjaman.cancel');

    // ===== ANGSURAN =====
    Route::get('/angsuran', [AngsuranController::class, 'index'])
        ->name('angsuran.index');

    Route::get('/angsuran/{angsuran}', [AngsuranController::class, 'show'])
        ->name('angsuran.show');

    Route::post('/angsuran/{angsuran}/bayar', [AngsuranController::class, 'pay'])
        ->name('angsuran.pay');

    // ===== SIMPANAN =====
    Route::resource('simpanans', SimpananController::class);

    // ===== LAPORAN =====

    // Laporan pinjaman
    Route::get('/laporan/pinjaman', [
        LaporanController::class,
        'index'
    ])->name('laporan.pinjaman');

    // Cetak laporan PDF
    Route::get('/laporan/pinjaman/pdf', [
        LaporanController::class,
        'cetakLaporan'
    ])->name('laporan.pinjaman.pdf');

    // Cetak kartu angsuran
    Route::get('/laporan/kartu-angsuran/{pinjaman}', [
        LaporanController::class,
        'kartuAngsuran'
    ])->name('laporan.kartu');

    // Cetak kwitansi
    Route::get('/laporan/kwitansi/{pembayaran}', [
        LaporanController::class,
        'kwitansi'
    ])->name('laporan.kwitansi');

    // ===== USER (KHUSUS ADMIN) =====
    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::resource('users', UserController::class)
            ->only(['index', 'update']);
    });
});

require __DIR__ . '/auth.php';
