<?php

namespace App\Http\Controllers;

use App\Models\BukuRusak;
use App\Models\Buku;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuRusakController extends Controller
{
    // Menampilkan daftar buku rusak + Filter Tanggal
    public function index(Request $request)
    {
        $query = BukuRusak::with(['buku', 'peminjaman.user']);

        // Filter Tanggal
        if ($request->has(['start_date', 'end_date']) && $request->start_date != null) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $dataRusak = $query->latest()->paginate(10);

        return view('buku_rusak.index', compact('dataRusak'));
    }

    // Form Edit (Hanya untuk edit catatan/jenis kerusakan, bukan ganti buku)
    public function edit($id)
    {
        $rusak = BukuRusak::with('buku')->findOrFail($id);
        return view('buku_rusak.edit', compact('rusak'));
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_buku' => 'required|string',
            'jenis_kerusakan' => 'required',
            'catatan' => 'nullable|string',
        ]);

        $rusak = BukuRusak::findOrFail($id);

        $rusak->update([
            'nomor_buku' => $request->nomor_buku,
            'jenis_kerusakan' => $request->jenis_kerusakan,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('buku-rusak.index')->with('success', 'Data kerusakan berhasil diperbarui.');
    }

    // Hapus Data (PENTING: Mengembalikan Stok Buku)
    public function destroy($id)
    {
        $rusak = BukuRusak::findOrFail($id);

        // Ambil ID Buku sebelum data dihapus
        $bukuId = $rusak->buku_id;

        // Hapus data kerusakan
        $rusak->delete();

        // OPSI: Kembalikan stok buku (+1) karena dianggap batal rusak/sudah diperbaiki
        // Jika Anda TIDAK ingin stok kembali, hapus 3 baris di bawah ini.
        $buku = Buku::find($bukuId);
        if($buku) {
            $buku->increment('stok');
        }

        return redirect()->route('buku-rusak.index')->with('success', 'Data dihapus dan stok buku dikembalikan.');
    }

    // Cetak Laporan PDF/Print View
    private function getFilteredRusak(Request $request)
    {
        // Eager load relasi agar query ringan
        $query = BukuRusak::with(['buku', 'peminjaman.user']);

        // 1. Filter Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // 2. Filter Jenis Kerusakan
        if ($request->has('jenis_kerusakan') && $request->jenis_kerusakan != 'semua') {
            $query->where('jenis_kerusakan', $request->jenis_kerusakan);
        }

        return $query->latest()->get();
    }

    /**
     * Menampilkan Halaman Laporan (View HTML)
     */
    public function laporan(Request $request)
    {
        $dataRusak = $this->getFilteredRusak($request);

        // Return ke view index laporan yang sudah kita buat sebelumnya
        return view('buku_rusak.laporan', compact('dataRusak'));
    }

    /**
     * Menangani Download PDF dan Excel (Stream)
     */
    public function download(Request $request)
    {
        $data = $this->getFilteredRusak($request);
        $jenis = $request->input('jenis');
        $namaFile = 'Laporan_Buku_Rusak_' . date('d-m-Y');

        // 1. PDF
        if ($jenis == 'pdf') {
            // Pastikan view 'laporan.rusak_pdf' sudah ada (dari chat sebelumnya)
            $pdf = Pdf::loadView('buku_rusak.pdf', ['dataRusak' => $data]);
            $pdf->setPaper('a4', 'portrait');
            return $pdf->download($namaFile . '.pdf');
        }

        // 2. EXCEL (Native PHP Stream)
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

                // Header HTML untuk Excel
                fwrite($file, '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
                <head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
                <style>table{border-collapse:collapse;} td,th{border:1px solid #000; padding:5px; vertical-align:top;}</style>
                </head><body><table>');

                // Judul Kolom (Sesuaikan dengan data Buku Rusak)
                fwrite($file, '<thead><tr>
                    <th style="background:#eee;">No</th>
                    <th style="background:#eee;">Tanggal Lapor</th>
                    <th style="background:#eee;">Judul Buku</th>
                    <th style="background:#eee;">Kode Buku</th>
                    <th style="background:#eee;">Peminjam</th>
                    <th style="background:#eee;">Jenis Kerusakan</th>
                    <th style="background:#eee;">Catatan</th>
                </tr></thead><tbody>');

                // Isi Data (Looping)
                foreach ($data as $key => $row) {
                    fwrite($file, '<tr>
                        <td align="center">' . ($key + 1) . '</td>
                        <td>' . $row->created_at->format('d/m/Y') . '</td>
                        <td>' . ($row->buku->judul ?? 'Buku Terhapus') . '</td>
                        <td>' . $row->nomor_buku . '</td>
                        <td>' . ($row->peminjaman->user->name ?? 'User Terhapus') . '</td>
                        <td align="center" style="font-weight:bold;">' . $row->jenis_kerusakan . '</td>
                        <td>' . $row->catatan . '</td>
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
