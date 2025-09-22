<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('buku', BukuController::class);

Route::middleware(['auth'])->group(function () {
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])
        ->name('peminjaman.kembalikan');
});

Route::resource('kategori', KategoriController::class);

Route::middleware(['auth'])->group(function () {
    Route::resource('siswa', SiswaController::class);

    // Route tambahan untuk cetak
    Route::get('siswa/{siswa}/cetak', [SiswaController::class, 'cetak'])->name('siswa.cetak');
    // Route::get('/siswa/{siswa}/cetak-img', [SiswaController::class, 'cetakImg'])->name('siswa.cetak.img');

});


require __DIR__.'/auth.php';
