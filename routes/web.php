<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    BukuController,
    PeminjamanController,
    KategoriController,
    SiswaController,
    ReservasiController,
    GuruController,
    AuthController
};

// ===========================
// HALAMAN UTAMA
// ===========================
Route::get('/', fn() => redirect()->route('login'));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/buku-tamu', [BukuController::class, 'tamu'])->name('buku.tamu');
Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');


// ===========================
// ROUTE DENGAN MIDDLEWARE AUTH
// ===========================
Route::middleware('auth')->group(function () {

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');

    // BUKU
    Route::resource('buku', BukuController::class);

    // ===========================
    // PEMINJAMAN
    // ===========================
    Route::get('/peminjaman/refresh', [PeminjamanController::class, 'refresh'])->name('peminjaman.refresh');
    Route::get('/peminjaman/tampil', [PeminjamanController::class, 'tampil'])->name('peminjaman.tampil');

    Route::resource('peminjaman', PeminjamanController::class);

    // Konfirmasi peminjaman
    Route::post('/peminjaman/konfirmasi/{id}', [PeminjamanController::class, 'konfirmasi'])
        ->name('peminjaman.konfirmasi');

    // Kembalikan buku (POST ONLY)
    Route::post('/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])
        ->name('peminjaman.kembalikan');

    // DENDA
    Route::get('/denda', [PeminjamanController::class, 'kelolaDenda'])->name('peminjaman.keloladenda');
    Route::post('/peminjaman/{id}/bayar-denda', [PeminjamanController::class, 'bayarDenda'])->name('peminjaman.bayarDenda');

    // Halaman pengembalian admin
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalianIndex'])->name('pengembalian.index');


    // ===========================
    // RESERVASI
    // ===========================
    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');

    // Siswa melakukan reservasi
    Route::post('/reservasi/konfirmasi/{bukuId}', [PeminjamanController::class, 'reservasi'])
        ->name('reservasi.konfirmasi');

    // Admin konfirmasi reservasi + kirim WhatsApp
    Route::post('/reservasi-admin/konfirmasi/{id}', [ReservasiController::class, 'konfirmasiReservasi'])
        ->name('reservasi.admin.konfirmasi');

});


// ===========================
// ROUTE PUSTAKAWAN
// ===========================
Route::middleware(['auth', 'role:pustakawan'])->group(function () {

    Route::resource('kategori', KategoriController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('guru', GuruController::class);
});

// CETAK SISWA
Route::get('/siswa/{siswa}/cetak', [SiswaController::class, 'cetak'])->name('siswa.cetak');


// ===========================
// RESET PASSWORD CUSTOM
// ===========================
Route::post('/forgot-password', [AuthController::class, 'checkEmail'])->name('password.check');

Route::get('/reset-password/email/{email}', [AuthController::class, 'showForm'])
    ->name('password.reset.form');

Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');

Route::post('/reset-password/submit', [AuthController::class, 'resetSubmit'])
    ->name('password.reset.submit');


// FORM lupa password
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])
    ->name('password.request');

// SUBMIT email lupa password
Route::post('/forgot-password', [AuthController::class, 'checkEmail'])
    ->name('password.email');

Route::post('/forgot-password/check', [AuthController::class, 'checkEmail'])
    ->name('password.check');


// ===========================
// AUTH DEFAULT LARAVEL
// ===========================
require __DIR__ . '/auth.php';
