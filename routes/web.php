<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    BukuController,
    PeminjamanController,
    KategoriController,
    SiswaController,
    ReservasiController,
    GuruController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===========================
// HALAMAN UTAMA & DASHBOARD
// ===========================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/buku-tamu', [BukuController::class, 'tamu'])->name('buku.tamu');
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
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])
    ->name('profile.update-photo')
    ->middleware('auth');

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
    Route::post('/peminjaman/konfirmasi/{id}', [PeminjamanController::class, 'konfirmasi'])->name('peminjaman.konfirmasi');

    Route::post('/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.anggota');

    Route::get('/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    // Halaman kelola denda
    Route::get('/denda', [PeminjamanController::class, 'kelolaDenda'])->name('peminjaman.keloladenda');
    Route::post('/peminjaman/{id}/bayar-denda', [PeminjamanController::class, 'bayarDenda'])
        ->name('peminjaman.bayarDenda');

    /*
    |------------------------------
    | Pengembalian Admin
    |------------------------------
    */

    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalianIndex'])->name('pengembalian.index');



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
    Route::resource('guru', GuruController::class);
});
Route::get('siswa/{siswa}/cetak', [SiswaController::class, 'cetak'])->name('siswa.cetak');



// ===========================
// AUTH (LOGIN, REGISTER, DLL.)
// ===========================
require __DIR__ . '/auth.php';
