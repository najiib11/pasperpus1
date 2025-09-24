<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Collection;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])->get();

    // Grouping reservasi berdasarkan buku_id
        $reservasiGrouped = $peminjamans
            ->where('status', 'reservasi')
            ->sortBy('created_at') // urut berdasarkan waktu isi form
            ->groupBy('buku_id');

        return view('peminjaman.index', [
            'peminjamans' => $peminjamans,
            'reservasiGrouped' => $reservasiGrouped,
        ]);
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
        'jumlah' => 'required|integer|min:1',
    ]);

    $buku = Buku::findOrFail($request->buku_id);
    $jumlah = $request->jumlah;

    // Cek apakah stok cukup
    $status = $buku->stok >= $jumlah ? 'dipinjam' : 'reservasi';

    if ($status === 'dipinjam') {
        $buku->decrement('stok', $jumlah);
    }

    Peminjaman::create([
        'user_id' => $request->user_id,
        'buku_id' => $buku->id,
        'jumlah' => $jumlah,
        'tanggal_pinjam' => now(),
        'tenggat' => now()->addDays(7),
        'status' => $status,
    ]);

    return redirect()->route('peminjaman.index')
        ->with('success', "Peminjaman berhasil ditambahkan dengan status: {$status}");
}

    public function show(Peminjaman $peminjaman)
    {
        return view('peminjaman.edit', data: ['peminjaman' => $peminjaman->with("user")->get(), 'buku' => Buku::all()]);
    }

    /**
     * Form edit peminjaman.
     */
    public function edit(Peminjaman $peminjaman)
    {
        $buku = Buku::all();
        $users = User::all();
        return view('peminjaman.edit', data: ['peminjaman' => $peminjaman, 'buku' => Buku::all()]);
    }

    /**
     * Update data peminjaman.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'nullable|date',
            'tenggat' => 'required|date',
            'status' => 'required|in:dipinjam,dikembalikan,reservasi',
        ]);

        $data = $request->only([
            'user_id',
            'buku_id',
            'jumlah',
            'tanggal_pinjam',
            'tenggat',
            'status',
        ]);

        // Jika status dikembalikan dan tanggal_kembali belum ada
        if ($request->status === 'dikembalikan' && $peminjaman->tanggal_kembali === null) {
            $data['tanggal_kembali'] = now();
        }

        // Jika status diubah ke "dipinjam" dan tanggal_pinjam kosong
        if ($request->status === 'dipinjam' && empty($data['tanggal_pinjam'])) {
            $data['tanggal_pinjam'] = now()->toDateString();
        }

        $peminjaman->update($data);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
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

    public function daftarReservasi($bukuId)
    {
        $reservasi = Peminjaman::where('buku_id', $bukuId)
            ->where('status', 'reservasi')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('peminjaman.reservasi', compact('reservasi'));
    }

    // tambah ke antrian reservasi
    public function reservasi(Request $request, $bukuId)
    {
        Peminjaman::create([
            'user_id' => auth()->id(),
            'buku_id' => $bukuId,
            'tanggal_pinjam' => now(),
            'tenggat' => now()->addDays(7),
            'status' => 'reservasi'
        ]);

        return redirect()->back()->with('success', 'Reservasi berhasil ditambahkan ke antrian!');
    }
}
