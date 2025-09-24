<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Cookie;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::with('buku')->get(); // 'bukus' relasi hasMany di model Kategori
            return view('buku.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) // <- tambahkan Request $request
    {
        $kategori_id = $request->get('kategori_id'); // baca kategori_id dari query string
        $kategoris = Kategori::all();

        return view('buku.create', compact('kategoris', 'kategori_id'));
    }

    /**
     * Store a newly created resource in storage.
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
            'kategori_id' => 'nullable|exists:kategoris,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('buku', 'public'); // simpan di storage/app/public/buku
            $data['gambar'] = $path;
        }

        $created = Buku::create($data);


        if($created){
            return redirect()->route("buku.index")->with('success', 'Buku berhasil ditambahkan!');
        }


        return redirect()->route('buku.index')->with('failure', 'Buku Gagal ditambahkan!');
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    /**
 * Show the form for editing the specified resource.
 */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all(); // untuk dropdown kategori
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'stok' => 'required|integer|min:0',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->stok = $request->input('stok');
        $buku->save();

        return redirect()->route('buku.show', $buku->id)
            ->with('success', 'Stok buku berhasil diperbarui.');
    }
}
