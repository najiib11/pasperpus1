<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // Tambahan untuk PDF

class ReservasiController extends Controller
{
    // --- HALAMAN UTAMA RESERVASI ---
    public function index()
    {
        $reservasiGrouped = Peminjaman::with(['user', 'buku'])
            ->where('status', 'reservasi')
            ->get()
            ->groupBy('buku_id');

        return view('reservasi.index', compact('reservasiGrouped'));
    }

    // --- LOGIC KONFIRMASI WA (ASLI) ---
    public function konfirmasiReservasi($id)
    {
        $reservasi = Peminjaman::findOrFail($id);
        $reservasi->status = 'dipinjam';
        $reservasi->tanggal_pinjam = now(); // Update tanggal pinjam jadi sekarang
        $reservasi->tenggat = now()->addDays(7); // Set tenggat baru
        $reservasi->save();

        // Kurangi stok buku
        if ($reservasi->buku) {
            $reservasi->buku->decrement('stok', $reservasi->jumlah);
        }

        // Ambil nomor telepon
        $nomorSiswa = optional($reservasi->user->siswa)->telepon;
        $nomorGuru = optional($reservasi->user->guru)->telepon;
        $nomor = $nomorSiswa ?: $nomorGuru;

        if (!$nomor) {
            return back()->with('error', 'Nomor WhatsApp tidak ditemukan.');
        }

        // Format pesan
        $pesan = "Halo *{$reservasi->user->name}*,\n\n" .
            "Reservasi bukumu dengan judul *{$reservasi->buku->judul}* sudah *READY*.\n" .
            "Silakan ambil ke perpustakaan. Terima kasih 🙏";

        // Kirim WA
        $this->kirimWhatsapp($nomor, $pesan);

        return back()->with('success', 'Reservasi dikonfirmasi & WhatsApp terkirim!');
    }

    private function kirimWhatsapp($nomor, $pesan)
    {
        $token = env('WHAPI_TOKEN');
        $baseUrl = env('WHAPI_BASE_URL');
        $nomor = preg_replace('/^0/', '62', $nomor);

        try {
            Http::withHeaders([
                'Authorization' => "Bearer $token",
                'Content-Type' => 'application/json',
            ])->post($baseUrl . 'messages/text', [
                'to' => $nomor,
                'body' => $pesan,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA: ' . $e->getMessage());
        }
    }

    // ==========================================
    // BAGIAN BARU: LAPORAN & DOWNLOAD
    // ==========================================

    private function getFilteredReservasi(Request $request)
    {
        // Ambil hanya yang statusnya 'reservasi'
        $query = Peminjaman::with(['user', 'buku'])
            ->where('status', 'reservasi');

        // Filter Berdasarkan Tanggal Reservasi (tanggal_pinjam saat booking)
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_akhir);
        }

        // Urutkan dari yang terbaru
        return $query->orderBy('tanggal_pinjam', 'desc')->get();
    }

    public function laporanReservasi(Request $request)
    {
        $reservasis = $this->getFilteredReservasi($request);
        return view('reservasi.laporan', compact('reservasis'));
    }

    public function downloadReservasi(Request $request)
    {
        $data = $this->getFilteredReservasi($request);
        $jenis = $request->input('jenis');
        $namaFile = 'Laporan_Reservasi_' . date('d-m-Y');

        // 1. PDF (DomPDF)
        if ($jenis == 'pdf') {
            $pdf = Pdf::loadView('reservasi.pdf', ['reservasis' => $data]);
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
                    <th style="background:#eee;">Tgl Reservasi</th>
                    <th style="background:#eee;">Status</th>
                </tr></thead><tbody>');

                // Isi Data
                foreach ($data as $key => $row) {
                    fwrite($file, '<tr>
                        <td align="center">' . ($key + 1) . '</td>
                        <td>' . ($row->user->name ?? '-') . '</td>
                        <td>' . ($row->buku->judul ?? '-') . '</td>
                        <td align="center">' . $row->jumlah . '</td>
                        <td align="center">' . ($row->tanggal_pinjam ? $row->tanggal_pinjam->format('d/m/Y') : '-') . '</td>
                        <td align="center">Reservasi</td>
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
