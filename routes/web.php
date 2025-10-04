<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    BukuController,
    PeminjamanController,
    KategoriController,
    SiswaController,
    ReservasiController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===========================
// HALAMAN UTAMA & DASHBOARD
// ===========================
Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ===========================
// ROUTE DENGAN MIDDLEWARE AUTH
// ===========================
Route::middleware('auth')->group(function () {
    /*
    |------------------------------
    | PROFILE
    |------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |------------------------------
    | BUKU
    |------------------------------
    */
    Route::resource('buku', BukuController::class);

    /*
    |------------------------------
    | PEMINJAMAN
    |------------------------------
    */
    Route::get('/peminjaman/refresh', [PeminjamanController::class, 'refresh'])->name('peminjaman.refresh');
    Route::get('/peminjaman/tampil', [PeminjamanController::class, 'tampil'])
    ->name('peminjaman.tampil');
    Route::resource('peminjaman', PeminjamanController::class);


    Route::post('/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.anggota');

    Route::get('/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');


    /*
    |------------------------------
    | RESERVASI
    |------------------------------
    */
    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::post('/reservasi/konfirmasi/{bukuId}', [PeminjamanController::class, 'reservasi'])
        ->name('reservasi.konfirmasi');
});

// ===========================
// ROUTE KHUSUS PUSTAKAWAN
// ===========================
Route::middleware(['auth', 'role:pustakawan'])->group(function () {
    /*
    |------------------------------
    | KATEGORI
    |------------------------------
    */
    Route::resource('kategori', KategoriController::class);

    /*
    |------------------------------
    | SISWA
    |------------------------------
    */
    Route::resource('siswa', SiswaController::class);
    Route::get('siswa/{siswa}/cetak', [SiswaController::class, 'cetak'])->name('siswa.cetak');
});

// ===========================
// AUTH (LOGIN, REGISTER, DLL.)
// ===========================
require __DIR__ . '/auth.php';
