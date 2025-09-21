<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Carbon\Carbon;
use App\Models\User;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with('buku','user')->get();
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $buku = Buku::all();
        $users = User::all();
        return view('peminjaman.create', compact('buku','users'));
    }

    /**
     * Simpan data peminjaman baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        // cek stok
        $status = $buku->stok > 0 ? 'dipinjam' : 'reservasi';

        if ($status === 'dipinjam') {
            $buku->decrement('stok');
        }

        Peminjaman::create([
            'user_id' => $request->user_id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => Carbon::now(),
            'tenggat' => Carbon::now()->addDays(7),
            'status' => $status,
        ]);

        return redirect()->route('peminjaman.index')
            ->with('success', "Peminjaman berhasil ditambahkan dengan status: {$status}");
    }

    /**
     * Form edit peminjaman.
     */
    public function edit(Peminjaman $peminjaman)
    {
        $buku = Buku::all();
        $users = User::all();
        return view('peminjaman.edit', compact('peminjaman','buku','users'));
    }

    /**
     * Update data peminjaman.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'tenggat' => 'required|date',
        ]);

        $peminjaman->update($request->all());

        return redirect()->route('peminjaman.index')
            ->with('success','Data peminjaman berhasil diperbarui.');
    }

    /**
     * Hapus peminjaman.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')
            ->with('success','Data peminjaman berhasil dihapus.');
    }

    /**
     * Proses pengembalian buku.
     */
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->tanggal_kembali = Carbon::now();

        // Hitung denda kalau lewat tenggat
        if (Carbon::now()->gt($peminjaman->tenggat)) {
            $hariTelat = Carbon::now()->diffInDays($peminjaman->tenggat, false);
            $peminjaman->denda = abs($hariTelat) * 1000;
        }

        $peminjaman->status = 'dikembalikan';
        $peminjaman->save();

        // Tambah stok buku kembali
        $peminjaman->buku->increment('stok');

        return redirect()->route('peminjaman.index')
            ->with('success', 'Buku berhasil dikembalikan!');
    }
}
