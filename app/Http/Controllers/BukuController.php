<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Buku;
use App\Models\Siswa;
use App\Models\Kategori;

use Illuminate\Http\Request;
use App\Helpers\WhatsAppHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // ✅ tambahkan ini
use Barryvdh\DomPDF\Facade\Pdf; // <--- Tambahkan ini di atas

class BukuController extends Controller
{
    /**
     * Tampilkan daftar buku per kategori.
     */
    public function index()
    {
        $kategoris = Kategori::with(relations: 'buku')->get();
        $roles = \Spatie\Permission\Models\Role::all();
        return view('buku.index', compact('kategoris', 'roles'));
    }

    /**
     * Form tambah buku.
     */
    public function create(Request $request)
    {
        $kategori_id = $request->get('kategori_id');
        $kategoris = Kategori::all();

        return view('buku.create', compact('kategoris', 'kategori_id'));
    }

    /**
     * Simpan buku baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|digits:4|integer',
            'jumlah_halaman' => 'required|integer',
            'sumber_pengadaan' => 'required|in:hibah,pemerintah',
            'kategori_id' => 'required|exists:kategoris,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'judul',
            'penulis',
            'penerbit',
            'tahun_terbit',
            'jumlah_halaman',
            'sumber_pengadaan',
            'kategori_id',
            'stok'
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('buku', 'public');
            $data['gambar'] = $path;
        }

        $created = Buku::create($data);

        return $created
            ? redirect()->route("buku.index")->with('success', 'Buku berhasil ditambahkan!')
            : redirect()->route('buku.index')->with('failure', 'Buku gagal ditambahkan!');
    }

    /**
     * Detail buku.
     */
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        $user = Auth::user();
        $roles = $user->getRoleNames(); // Hasilnya berupa koleksi (collection)
        $pesan = '';

        // Cek jika user memiliki role 'anggota' (atau 'user')
        if ($user && $user->getRoleNames('anggota')) {
            $sudahPinjam = \App\Models\Peminjaman::where('user_id', $user->id)
                ->where('buku_id', $buku->id)
                ->whereIn('status', ['dipinjam', 'reservasi']) // cek dua status sekaligus
                ->exists();

            if ($sudahPinjam) {
                $pesan = 'Siswa hanya boleh meminjam satu buku per setiap buku.';
            }
        }

        // dd($id);
        // dd($pesan);

        return view('buku.show', compact('buku', 'pesan'));
    }

    /**
     * Form edit buku.
     */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Update buku (stok dan info lain).
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'penulis' => 'nullable|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|digits:4|integer',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'sumber_pengadaan' => 'nullable|in:hibah,pemerintah',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'stok' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $buku = Buku::findOrFail($id);

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($buku->gambar && Storage::disk('public')->exists($buku->gambar)) {
                Storage::disk('public')->delete($buku->gambar);
            }

            // Simpan gambar baru
            $validated['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        // Update data buku
        $buku->update($validated);

        // Kirim notifikasi WhatsApp jika stok > 0
        if ($buku->stok > 0) {
            try {
                Http::withHeaders([
                    'Authorization' => 'JMyhDU5DNLsdRZq4hBxj', // API Key Fonnte
                ])->post('https://api.fonnte.com/send', [
                            'target' => '6287793183539',
                            'message' => "📚 Halo, buku *{$buku->judul}* sekarang sudah tersedia di perpustakaan. Silakan segera dipinjam ya 😊",
                        ]);
            } catch (\Exception $e) {
                // Simpan log error agar tidak mengganggu proses update
                Log::error('Gagal mengirim pesan ke Fonnte: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('buku.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }


    /**
     * Hapus buku.
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
 public function tamu(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            // Jika ada pencarian, ambil buku berdasarkan pencarian
            $bukuResults = Buku::where('judul', 'like', '%' . $search . '%')
                ->orWhere('penulis', 'like', '%' . $search . '%')
                ->orWhere('penerbit', 'like', '%' . $search . '%')
                ->orWhereHas('kategori', function($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%');
                })
                ->with('kategori')
                ->get()
                ->groupBy('kategori_id');

            $kategoris = Kategori::with('buku')
                ->whereHas('buku', function($query) use ($search) {
                    $query->where('judul', 'like', '%' . $search . '%')
                          ->orWhere('penulis', 'like', '%' . $search . '%')
                          ->orWhere('penerbit', 'like', '%' . $search . '%');
                })
                ->get();

            return view('buku.tamu', compact('kategoris', 'search', 'bukuResults'));
        }

        // Jika tidak ada pencarian, tampilkan semua
        $kategoris = Kategori::with('buku')->get();
        $bukuResults = collect();

        return view('buku.tamu', compact('kategoris', 'search', 'bukuResults'));
    }

    public function search(Request $request)
    {
        return $this->tamu($request);
    }

    // ==========================================
    // BAGIAN LAPORAN BUKU
    // ==========================================

    private function getFilteredBuku(Request $request)
    {
        $query = Buku::with('kategori');

        // Filter Kategori
        if ($request->filled('kategori_id') && $request->kategori_id != 'semua') {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter Sumber Pengadaan
        if ($request->filled('sumber_pengadaan') && $request->sumber_pengadaan != 'semua') {
            $query->where('sumber_pengadaan', $request->sumber_pengadaan);
        }

        // Filter Tahun Terbit
        if ($request->filled('tahun_terbit')) {
            $query->where('tahun_terbit', $request->tahun_terbit);
        }

        return $query->orderBy('judul', 'asc')->get();
    }

    public function laporanBuku(Request $request)
    {
        $bukus = $this->getFilteredBuku($request);
        $kategoris = Kategori::all(); // Untuk dropdown filter

        return view('buku.laporan', compact('bukus', 'kategoris'));
    }

    public function downloadBuku(Request $request)
    {
        $data = $this->getFilteredBuku($request);
        $jenis = $request->input('jenis');
        $namaFile = 'Laporan_Data_Buku_' . date('d-m-Y');

        // 1. PDF
        if ($jenis == 'pdf') {
            $pdf = Pdf::loadView('buku.pdf', ['bukus' => $data]);
            // Set paper landscape karena kolomnya banyak
            $pdf->setPaper('a4', 'landscape');
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
                    <th style="background:#eee;">Judul Buku</th>
                    <th style="background:#eee;">Penulis</th>
                    <th style="background:#eee;">Penerbit</th>
                    <th style="background:#eee;">Tahun</th>
                    <th style="background:#eee;">Kategori</th>
                    <th style="background:#eee;">Sumber</th>
                    <th style="background:#eee;">Stok</th>
                </tr></thead><tbody>');

                foreach ($data as $key => $row) {
                    fwrite($file, '<tr>
                        <td align="center">' . ($key + 1) . '</td>
                        <td>' . $row->judul . '</td>
                        <td>' . $row->penulis . '</td>
                        <td>' . $row->penerbit . '</td>
                        <td align="center">' . $row->tahun_terbit . '</td>
                        <td>' . ($row->kategori->nama ?? '-') . '</td>
                        <td>' . ucfirst($row->sumber_pengadaan) . '</td>
                        <td align="center">' . $row->stok . '</td>
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
