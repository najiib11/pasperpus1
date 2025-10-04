<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    // Menampilkan semua buku dalam kategori tertentu
    public function show($id)
    {
        $kategori = Kategori::with('buku')->findOrFail($id);

        return view('kategori.show', compact('kategori'));
    }
    // Form tambah kategori
    public function create()
    {
        return view('kategori.create');
    }

    // Simpan kategori baru
   public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $nama = trim($request->nama);
        $kata = explode(' ', $nama);
        $kode = '';

        if (count($kata) == 1) {
            // Jika hanya 1 kata → ambil 3 huruf pertama
            $kode = strtoupper(substr($kata[0], 0, 3));
        } elseif (count($kata) == 2) {
            // Jika 2 kata → huruf pertama kata 1 + huruf pertama & terakhir kata 2
            $kata1 = $kata[0];
            $kata2 = $kata[1];
            $kode = strtoupper(substr($kata1, 0, 1) . substr($kata2, 0, 1) . substr($kata2, -1));
        } else {
            // Jika lebih dari 2 kata → ambil huruf pertama dari 3 kata pertama
            $kode = strtoupper(substr($kata[0], 0, 1) . substr($kata[1], 0, 1) . substr($kata[2], 0, 1));
        }

        Kategori::create([
            'kode' => $kode,
            'nama' => $nama,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }


    // Form edit kategori
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    // Update kategori
    public function update(Request $request, $id)
    {
          $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:kategoris,kode',
        ]);
        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Hapus kategori
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
