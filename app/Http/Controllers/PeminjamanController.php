<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\BukuRusak;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Exports\PeminjamanExport;      // <--- Wajib
use Maatwebsite\Excel\Facades\Excel;   // <--- Wajib
use Barryvdh\DomPDF\Facade\Pdf;


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
        'status' => 'required',
        // Validasi array rusak (jika ada)
        'rusak' => 'nullable|array',
        'rusak.*.nomor_buku' => 'required_with:rusak|string',
        'rusak.*.jenis_kerusakan' => 'required_with:rusak|string',
        'rusak.*.catatan' => 'nullable|string',
    ]);

    $data = $request->only(['status', 'tanggal_pinjam', 'tenggat']); // Ambil field yang perlu saja

    // Logika Tanggal
    if ($data['status'] === 'dikembalikan' && $peminjaman->tanggal_kembali === null) {
        $data['tanggal_kembali'] = now();
    }
    if ($data['status'] === 'dipinjam' && empty($peminjaman->tanggal_pinjam)) {
        $data['tanggal_pinjam'] = now();
    }

    // Cek Status Sebelumnya
    $statusSebelumnya = $peminjaman->status;

    // Update Data Peminjaman Utama
    $peminjaman->update($data);

    // === LOGIKA PENGEMBALIAN & BUKU RUSAK ===
    // Jalankan logika ini HANYA jika status berubah menjadi 'dikembalikan'
    // agar stok tidak bertambah berkali-kali jika tombol simpan ditekan ulang.
    if ($statusSebelumnya !== 'dikembalikan' && $data['status'] === 'dikembalikan') {

        $buku = Buku::find($peminjaman->buku_id);

        // 1. Hitung Jumlah Buku Rusak
        $daftarRusak = $request->input('rusak', []); // Ambil array rusak, default kosong
        $jumlahRusak = count($daftarRusak);
        $jumlahPinjam = $peminjaman->jumlah;

        // Validasi Backend: Pastikan rusak tidak lebih dari pinjam
        if ($jumlahRusak > $jumlahPinjam) {
            return back()->withErrors(['msg' => 'Jumlah buku rusak melebihi jumlah peminjaman!']);
        }

        // 2. Hitung yang kembali ke Stok (Bagus)
        // Rumus: Stok Kembali = Pinjam - Rusak
        $stokKembali = $jumlahPinjam - $jumlahRusak;

        // 3. Update Stok Buku
        if ($stokKembali > 0) {
            $buku->stok += $stokKembali;
            $buku->save();
        }

        // 4. Simpan Data Kerusakan (Buku yang rusak tidak masuk stok)
        if ($jumlahRusak > 0) {
            foreach ($daftarRusak as $item) {
                BukuRusak::create([
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id' => $peminjaman->buku_id,
                    'nomor_buku' => $item['nomor_buku'],
                    'jenis_kerusakan' => $item['jenis_kerusakan'],
                    'catatan' => $item['catatan'],
                ]);
            }
        }
    }

    return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman diperbarui. ' . (isset($jumlahRusak) && $jumlahRusak > 0 ? "$jumlahRusak buku dicatat rusak." : ""));
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

    private function getFilteredData(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        return $query->latest('tanggal_pinjam')->get();
    }

    public function laporan(Request $request)
    {
        $peminjamans = $this->getFilteredData($request);
        return view('peminjaman.laporan', compact('peminjamans'));
    }

    // --- DOWNLOAD EXCEL TANPA LIBRARY ---
    public function download(Request $request)
    {
        $data = $this->getFilteredData($request);
        $jenis = $request->input('jenis');
        $namaFile = 'Laporan_Peminjaman_' . date('d-m-Y');

        // 1. PDF (Pakai DomPDF)
        if ($jenis == 'pdf') {
            $pdf = Pdf::loadView('peminjaman.pdf', ['peminjamans' => $data]);
            return $pdf->download($namaFile . '.pdf');
        }

        // 2. EXCEL (Pakai HTML Table Trick - Tanpa Library Maatwebsite)
        elseif ($jenis == 'excel') {
            $headers = [
                "Content-Type" => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename=\"$namaFile.xls\"",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function() use($data) {
                $file = fopen('php://output', 'w');

                // Header HTML agar dibaca sebagai Excel
                fwrite($file, '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
                <head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
                <style>table{border-collapse:collapse;} td,th{border:1px solid #000; padding:5px;}</style>
                </head><body><table>');

                // Judul Kolom
                fwrite($file, '<thead><tr>
                    <th style="background-color:#eee;">No</th>
                    <th style="background-color:#eee;">Nama Peminjam</th>
                    <th style="background-color:#eee;">Judul Buku</th>
                    <th style="background-color:#eee;">Jumlah</th>
                    <th style="background-color:#eee;">Tgl Pinjam</th>
                    <th style="background-color:#eee;">Tenggat</th>
                    <th style="background-color:#eee;">Tgl Kembali</th>
                    <th style="background-color:#eee;">Status</th>
                    <th style="background-color:#eee;">Denda</th>
                </tr></thead><tbody>');

                // Isi Data
                foreach ($data as $key => $row) {
                    fwrite($file, '<tr>
                        <td align="center">' . ($key + 1) . '</td>
                        <td>' . ($row->user->name ?? '-') . '</td>
                        <td>' . ($row->buku->judul ?? '-') . '</td>
                        <td align="center">' . $row->jumlah . '</td>
                        <td align="center">' . ($row->tanggal_pinjam ? $row->tanggal_pinjam->format('d-m-Y') : '-') . '</td>
                        <td align="center">' . ($row->tenggat ? $row->tenggat->format('d-m-Y') : '-') . '</td>
                        <td align="center">' . ($row->tanggal_kembali ? $row->tanggal_kembali->format('d-m-Y') : '-') . '</td>
                        <td align="center">' . ucfirst($row->status) . '</td>
                        <td align="right">' . ($row->denda ?? 0) . '</td>
                    </tr>');
                }

                fwrite($file, '</tbody></table></body></html>');
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // 3. CSV (Optional)
        elseif ($jenis == 'csv') {
             $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$namaFile.csv",
            ];
            $columns = ['No', 'Nama', 'Buku', 'Jml', 'Tgl Pinjam', 'Tenggat', 'Tgl Kembali', 'Status', 'Denda'];
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $key => $row) {
                    fputcsv($file, [
                        $key + 1,
                        $row->user->name ?? '-',
                        $row->buku->judul ?? '-',
                        $row->jumlah,
                        $row->tanggal_pinjam ? $row->tanggal_pinjam->format('d-m-Y') : '-',
                        $row->tenggat ? $row->tenggat->format('d-m-Y') : '-',
                        $row->tanggal_kembali ? $row->tanggal_kembali->format('d-m-Y') : '-',
                        ucfirst($row->status),
                        $row->denda ?? 0
                    ]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back();
    }

    private function getFilteredPengembalian(Request $request)
    {
        // Ambil hanya yang statusnya 'dikembalikan'
        $query = Peminjaman::with(['user', 'buku'])
            ->where('status', 'dikembalikan');

        // Filter Berdasarkan Tanggal KEMBALI (Bukan tanggal pinjam)
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_kembali', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_kembali', '<=', $request->tanggal_akhir);
        }

        // Urutkan dari pengembalian terbaru
        return $query->orderBy('tanggal_kembali', 'desc')->get();
    }

    public function laporanPengembalian(Request $request)
    {
        $peminjamans = $this->getFilteredPengembalian($request);
        return view('pengembalian.laporan', compact('peminjamans'));
    }

    public function downloadPengembalian(Request $request)
    {
        $data = $this->getFilteredPengembalian($request);
        $jenis = $request->input('jenis');
        $namaFile = 'Laporan_Pengembalian_' . date('d-m-Y');

        // 1. PDF (Pakai DomPDF)
        if ($jenis == 'pdf') {
            $pdf = Pdf::loadView('pengembalian.pdf', ['peminjamans' => $data]);
            return $pdf->download($namaFile . '.pdf');
        }

        // 2. EXCEL (Native PHP - Tanpa Library)
        elseif ($jenis == 'excel') {
            $headers = [
                "Content-Type" => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename=\"$namaFile.xls\"",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function() use($data) {
                $file = fopen('php://output', 'w');

                // Header HTML Excel
                fwrite($file, '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
                <head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
                <style>table{border-collapse:collapse;} td,th{border:1px solid #000; padding:5px;}</style>
                </head><body><table>');

                // Judul Kolom
                fwrite($file, '<thead><tr>
                    <th style="background:#eee;">No</th>
                    <th style="background:#eee;">Nama Peminjam</th>
                    <th style="background:#eee;">Judul Buku</th>
                    <th style="background:#eee;">Jml</th>
                    <th style="background:#eee;">Tgl Pinjam</th>
                    <th style="background:#eee;">Tgl Kembali</th>
                    <th style="background:#eee;">Denda</th>
                </tr></thead><tbody>');

                // Isi Data
                foreach ($data as $key => $row) {
                    fwrite($file, '<tr>
                        <td align="center">' . ($key + 1) . '</td>
                        <td>' . ($row->user->name ?? '-') . '</td>
                        <td>' . ($row->buku->judul ?? '-') . '</td>
                        <td align="center">' . $row->jumlah . '</td>
                        <td align="center">' . ($row->tanggal_pinjam ? $row->tanggal_pinjam->format('d-m-Y') : '-') . '</td>
                        <td align="center">' . ($row->tanggal_kembali ? $row->tanggal_kembali->format('d-m-Y') : '-') . '</td>
                        <td align="right">' . ($row->denda ?? 0) . '</td>
                    </tr>');
                }

                fwrite($file, '</tbody></table></body></html>');
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back();
    }
    private function getFilteredDenda(Request $request)
    {
        // Ambil data yang dendanya LEBIH DARI 0
        $query = Peminjaman::with(['user', 'buku'])
            ->where('denda', '>', 0);

        // Filter Berdasarkan Tanggal Kembali (Karena denda muncul saat kembali)
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_kembali', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_kembali', '<=', $request->tanggal_akhir);
        }

        // Urutkan dari denda terbesar (biar kelihatan siapa yang bayar banyak)
        return $query->orderBy('tanggal_kembali', 'desc')->get();
    }

    public function laporanDenda(Request $request)
    {
        $dendas = $this->getFilteredDenda($request);
        return view('denda.laporan', compact('dendas'));
    }

    public function downloadDenda(Request $request)
    {
        $data = $this->getFilteredDenda($request);
        $jenis = $request->input('jenis');
        $namaFile = 'Laporan_Denda_' . date('d-m-Y');

        // 1. PDF
        if ($jenis == 'pdf') {
            $pdf = Pdf::loadView('denda.pdf', ['dendas' => $data]);
            return $pdf->download($namaFile . '.pdf');
        }

        // 2. EXCEL (Native PHP)
        elseif ($jenis == 'excel') {
            $headers = [
                "Content-Type" => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename=\"$namaFile.xls\"",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function() use($data) {
                $file = fopen('php://output', 'w');

                fwrite($file, '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
                <head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
                <style>table{border-collapse:collapse;} td,th{border:1px solid #000; padding:5px;}</style>
                </head><body><table>');

                // Judul Kolom
                fwrite($file, '<thead><tr>
                    <th style="background:#eee;">No</th>
                    <th style="background:#eee;">Nama Peminjam</th>
                    <th style="background:#eee;">Judul Buku</th>
                    <th style="background:#eee;">Total Buku</th>
                    <th style="background:#eee;">Tgl Kembali</th>
                    <th style="background:#eee;">Total Denda</th>
                </tr></thead><tbody>');

                foreach ($data as $key => $row) {
                    fwrite($file, '<tr>
                        <td align="center">' . ($key + 1) . '</td>
                        <td>' . ($row->user->name ?? '-') . '</td>
                        <td>' . ($row->buku->judul ?? '-') . '</td>
                        <td align="center">' . $row->jumlah . '</td>
                        <td align="center">' . ($row->tanggal_kembali ? $row->tanggal_kembali->format('d/m/Y') : '-') . '</td>
                        <td align="right">' . ($row->denda ?? 0) . '</td>
                    </tr>');
                }

                fwrite($file, '</tbody></table></body></html>');
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back();
    }
}
