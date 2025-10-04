<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

use App\Helpers\WhatsAppHelper;
use App\Models\Siswa;
use Illuminate\Support\Facades\Http;

class BukuController extends Controller
{
    /**
     * Tampilkan daftar buku per kategori.
     */
    public function index()
    {
        $kategoris = Kategori::with(relations: 'buku')->get();
        $roles =\Spatie\Permission\Models\Role::all();
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
            'judul'           => 'required',
            'penulis'         => 'required',
            'penerbit'        => 'required',
            'tahun_terbit'    => 'required|digits:4|integer',
            'jumlah_halaman'  => 'required|integer',
            'sumber_pengadaan'=> 'required|in:hibah,pemerintah',
            'kategori_id'     => 'required|exists:kategoris,id',
            'gambar'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'judul','penulis','penerbit','tahun_terbit',
            'jumlah_halaman','sumber_pengadaan','kategori_id','stok'
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
        return view('buku.show', compact('buku'));
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
    $request->validate([
        'judul'           => 'nullable',
        'penulis'         => 'nullable',
        'penerbit'        => 'nullable',
        'tahun_terbit'    => 'nullable|digits:4|integer',
        'jumlah_halaman'  => 'nullable|integer',
        'sumber_pengadaan'=> 'nullable|in:hibah,pemerintah',
        'kategori_id'     => ' nullable|exists:kategoris,id',
        'stok'            => 'nullable|integer|min:0',

    ]);

    $buku = Buku::findOrFail($id);

    $data = $request->all();

    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $path = $file->store('buku', 'public');
        $data['gambar'] = $path;
    }

    $buku->update($data);

if ($buku->stok > 0) {
    $response = Http::withHeaders([
        'Authorization' => '8dhtqM3Nr8UsKnqzM88u' // API Key Fonnte
    ])->post('https://api.fonnte.com/send', [
        'target' => '6287771600494', // nomor tujuan
        'message' => "📚 Halo, buku *{$buku->judul}* sekarang sudah tersedia di perpustakaan. Silakan segera dipinjam ya 😊",
    ]);
}

    return redirect()->route('buku.index', $buku->id)
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
}
