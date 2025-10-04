<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
            'email' => 'required|email|unique:siswa|unique:users,email',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
        ]);

        // Simpan ke tabel siswa
        $siswa = Siswa::create($request->all());

        // Buat akun user untuk siswa tersebut
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make('password123'), // bisa diset password = NISN
        ]);

        // Beri role 'anggota'
        $user->assignRole('anggota');

        return redirect()->route('siswa.index')->with('success', 'Data siswa dan akun pengguna berhasil ditambahkan.');
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
        // Validasi input
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn,' . $siswa->id,
            'email' => 'required|email|unique:siswa,email,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:20',
        ]);

        // Update data siswa
        $siswa->update($request->all());

        // Update juga data user (jika ada user dengan email yang sama)
        $user = \App\Models\User::where('email', $siswa->email)->first();

        if ($user) {
            // Update data user agar sinkron dengan data siswa
            $user->update([
                'name' => $request->nama,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'Data siswa dan user berhasil diperbarui.');
    }


    public function destroy(Siswa $siswa)
    {
        // Hapus user yang memiliki email sama dengan siswa ini
        $user = \App\Models\User::where('email', $siswa->email)->first();
        if ($user) {
            $user->delete();
        }

        // Hapus data siswa
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa dan akun pengguna berhasil dihapus');
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
