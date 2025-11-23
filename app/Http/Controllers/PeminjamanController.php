<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])
        ->where('status', 'dipinjam ')
        ->get();

        // Kelompokkan reservasi per buku
        $reservasiGrouped = $peminjamans
            ->where('status', 'reservasi')
            ->sortBy('created_at')
            ->groupBy('buku_id');

        return view('peminjaman.index', compact('peminjamans', 'reservasiGrouped'));
    }
    public function konfirmasi($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Pastikan status saat ini reservasi
            if ($peminjaman->status !== 'reservasi') {
                return back()->with('error', 'Hanya reservasi yang bisa dikonfirmasi.');
            }

            // Ubah status ke dipinjam
            $peminjaman->update([
                'status' => 'dipinjam',
            ]);

            return back()->with('success', 'Reservasi berhasil dikonfirmasi menjadi peminjaman!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Data peminjaman tidak ditemukan.');
        } catch (\Exception $e) {
            // Simpan log error jika perlu
            \Log::error('Kesalahan konfirmasi peminjaman: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengonfirmasi peminjaman.');
        }
    }

    public function tampil()
    {
        // ambil id user yang sedang login
        $userId = Auth::id();

        // ambil hanya peminjaman milik siswa login
        $peminjamans = Peminjaman::with(['user', 'buku'])
            ->where('user_id', $userId)
            ->get();

        return view('peminjaman.indexx', compact('peminjamans'));
    }

    public function create()
    {
        $buku = Buku::all();
        $users = User::all();
        return view('peminjaman.create', compact('buku', 'users'));
    }

    public function store(Request $request)
    {
        // 🔹 Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $buku = Buku::findOrFail($validated['buku_id']);
        $jumlah = $validated['jumlah'];

        // 🔹 Cegah peminjaman ganda oleh anggota
        if ($user->getRoleNames('anggota')) {
            $sudahPinjam = Peminjaman::where('user_id', $user->id)
                ->where('buku_id', $buku->id)
                ->whereNull('tanggal_kembali')
                ->exists();

            if ($sudahPinjam) {
                return back()
                    ->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.')
                    ->withInput();
            }
        }

        // 🔹 Tentukan status (dipinjam / reservasi)
        $status = $buku->stok >= $jumlah ? 'dipinjam' : 'reservasi';

        // 🔹 Jika stok cukup, kurangi stok
        if ($status === 'dipinjam') {
            if ($buku->stok < $jumlah) {
                return back()->withErrors([
                    'jumlah' => 'Stok buku tidak mencukupi untuk jumlah yang diminta.'
                ])->withInput();
            }
            $buku->decrement('stok', $jumlah);
        }

        // 🔹 Jika reservasi, hitung posisi antrian
        $antrian = null;
        if ($status === 'reservasi') {
            $last = Peminjaman::where('buku_id', $buku->id)
                ->where('status', 'reservasi')
                ->max('antrian');
            $antrian = $last ? $last + 1 : 1;
        }

        // 🔹 Simpan data peminjaman
        Peminjaman::create([
            'user_id' => $validated['user_id'],
            'buku_id' => $buku->id,
            'jumlah' => $jumlah,
            'tanggal_pinjam' => $status === 'dipinjam' ? now() : null,
            'tenggat' => $status === 'dipinjam' ? now()->addDays(7) : null,
            'status' => $status,
            'antrian' => $antrian,
        ]);

        // 🔹 Redirect berdasarkan role user
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            return redirect()
                ->route('peminjaman.index')
                ->with('success', "Peminjaman berhasil ditambahkan dengan status: {$status}");
        }

        return redirect()
            ->route('buku.index')
            ->with('success', 'Buku berhasil dipinjam atau masuk ke dalam antrian!');
    }

    public function refresh()
    {
        $peminjamans = Peminjaman::all(); // ambil semua, bisa dikembalikan atau dipinjam

        foreach ($peminjamans as $peminjaman) {
            // Pastikan ada tenggat dan tanggal_kembali
            if ($peminjaman->tenggat && $peminjaman->tanggal_kembali) {
                $tenggat = Carbon::parse($peminjaman->tenggat)->startOfDay();
                $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();

                // kalau tanggal kembali > tenggat → terlambat
                if ($tanggalKembali->gt($tenggat)) {
                    $hariTelat = $tenggat->diffInDays($tanggalKembali);
                    $peminjaman->denda = $hariTelat * 1000;
                } else {
                    $peminjaman->denda = 0;
                }

            } else {
                $peminjaman->denda = 0; // belum dikembalikan atau tidak ada tenggat
            }

            $peminjaman->save();
        }

        return redirect()->back()->with('success', 'Denda berhasil diperbarui');
    }

    public function show(Peminjaman $peminjaman)
    {
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $buku = Buku::all();
        $users = User::all();
        return view('peminjaman.edit', compact('peminjaman', 'buku', 'users'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'nullable|date',
            'tenggat' => 'nullable|date',
            'status' => 'required|in:dipinjam,dikembalikan,reservasi',
        ]);
    
        $data = $request->all();
    
        // Tambah tanggal kembali
        if ($data['status'] === 'dikembalikan' && $peminjaman->tanggal_kembali === null) {
            $data['tanggal_kembali'] = now();
        }
    
        // Isi tanggal pinjam kalau dipinjam
        if ($data['status'] === 'dipinjam' && empty($data['tanggal_pinjam'])) {
            $data['tanggal_pinjam'] = now();
        }
    
        $statusSebelumnya = $peminjaman->status;
        
        $peminjaman->update($data);
    
        // === Tambah stok buku saat dikembalikan ===
        if ($statusSebelumnya !== 'dikembalikan' && $data['status'] === 'dikembalikan') {
            $buku = Buku::find($peminjaman->buku_id);
            $buku->stok += $peminjaman->jumlah;
            $buku->save();
        }
    
        return redirect()->route('pengembalian.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }
    

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('peminjaman.tampil')->with('success', 'Data peminjaman berhasil dihapus.');
    }


    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Gunakan tanggal hari ini TANPA jam
        $tanggalKembali = Carbon::today()->toDateString();
        $peminjaman->tanggal_kembali = $tanggalKembali;

        if ($peminjaman->tenggat) {
            $tenggat = Carbon::parse($peminjaman->tenggat)->startOfDay();

            if (Carbon::parse($tanggalKembali)->gt($tenggat)) {
                $hariTelat = $tenggat->diffInDays($tanggalKembali);
                $peminjaman->denda = $hariTelat * 1000;
            } else {
                $peminjaman->denda = 0;
            }
        } else {
            $peminjaman->denda = 0;
        }

        $peminjaman->status = 'dikembalikan';
        $peminjaman->tanggal_kembali = now();
        $peminjaman->save();

        $peminjaman->buku->increment('stok', $peminjaman->jumlah);

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan! Denda: Rp ' . number_format($peminjaman->denda, 0, ',', '.'));
    }


    public function daftarReservasi($bukuId)
    {
        $reservasi = Peminjaman::where('buku_id', $bukuId)
            ->where('status', 'reservasi')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('peminjaman.reservasi', compact('reservasi'));
    }

    public function reservasi(Request $request, $bukuId)
    {
        // Validasi input tanggal
        $request->validate([
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
        ]);
        $buku = Buku::findOrFail($bukuId);


        // Hitung tanggal tenggat (7 hari setelah tanggal_pinjam)
        $tanggalTenggat = Carbon::parse($request->tanggal_pinjam)->addDays(7);

        // Simpan data reservasi
        Peminjaman::create([
            'user_id' => auth()->id(),
            'buku_id' => $bukuId,
            'status' => 'reservasi',
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tenggat' => $tanggalTenggat,
        ]);

        // Redirect sesuai role
        return in_array(Auth::user()->id_role, [1, 2])
            ? redirect()->route('peminjaman.index')->with('success', 'Reservasi berhasil dibuat!')
            : redirect()->route('buku.index')->with('success', 'Reservasi berhasil dibuat!');
    }

    public function konfirmasiReservasi($id)
    {
        // Ambil data peminjaman berdasarkan ID
        $peminjaman = Peminjaman::findOrFail($id);

        // Pastikan status saat ini masih 'reservasi'
        if ($peminjaman->status !== 'reservasi') {
            return back()->with('error', 'Hanya reservasi yang bisa dikonfirmasi.');
        }

        // Ambil data buku yang bersangkutan
        $buku = Buku::findOrFail($peminjaman->buku_id);

        // Cek apakah stok buku masih tersedia
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku tidak tersedia untuk dipinjam.');
        }

        // Ubah status peminjaman menjadi 'dipinjam'
        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now(),
            'tenggat' => now()->addDays(7),
        ]);

        // Kurangi stok buku setelah konfirmasi berhasil
        $buku->decrement('stok', 1);

        return back()->with('success', 'Reservasi berhasil dikonfirmasi menjadi peminjaman!');
    }
    public function kelolaDenda()
    {
        // Ambil semua peminjaman yang sudah dikembalikan
        $peminjamans = Peminjaman::with(['user', 'buku'])
            ->where('status', 'dikembalikan')
            ->orderBy('tanggal_kembali', 'desc') // opsional: urut dari terbaru
            ->get();

        // Kirim data ke view keloladenda.blade.php
        return view('peminjaman.keloladenda', compact('peminjamans'));
    }
    public function bayarDenda($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Misal: tandai denda sudah dibayar
        $peminjaman->update(['denda' => 0]);

        return redirect()->route('peminjaman.keloladenda')->with('success', 'Denda berhasil dibayar!');
    }


    public function pengembalianIndex()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])
            ->where('status', 'dikembalikan')
            ->get();

        return view('pengembalian.index', compact('peminjamans'));
    }

}
