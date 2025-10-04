<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Facades\Image;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::all();
        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:siswa',
            'nama' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required',
            'email' => 'required|email|unique:siswa',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function show(Siswa $siswa)
    {
        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn,'.$siswa->id,
            'email' => 'required|email|unique:siswa,email,'.$siswa->id,
        ]);

        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }

   public function cetak(Siswa $siswa)
    {
        $pdf = Pdf::loadView('siswa.kartu', data: compact('siswa'))
                ->setPaper([0, 0, 297.64, 419.53], 'landscape'); // A6 landscape

        return $pdf->download('kartu-anggota-' . $siswa->nama . '.pdf');
    }

    // public function cetakImg(Siswa $siswa)
    // {
    //     // Render HTML kartu dari blade
    //     $html = view('siswa.kartu-img', compact('siswa'))->render();

    //     // Buat canvas image kosong (ukuran ID Card 1011x638 px @300dpi)
    //     $img = Image::canvas(1011, 638, '#ffffff');

    //     // Masukkan HTML ke dalam image (butuh gd/imagick yang support)
    //     $img->text(strip_tags($html), 50, 50, function($font) {
    //         $font->file(public_path('fonts/arial.ttf'));
    //         $font->size(24);
    //         $font->color('#000000');
    //     });

    //     return $img->response('png'); // tampilkan langsung sebagai PNG
    // }
}
