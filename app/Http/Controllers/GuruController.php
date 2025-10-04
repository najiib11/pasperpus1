<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    /**
     * Menampilkan daftar guru
     */
    public function index()
    {
        $guru = Guru::with('user')->latest()->paginate(10);
        return view('guru.index', compact('guru'));
    }

    /**
     * Menampilkan form tambah guru
     */
    public function create()
    {
        return view('guru.create');
    }

    /**
     * Menyimpan data guru baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:guru',
            'nama' => 'required',
            'email' => 'required|email|unique:guru|unique:users,email',
            'telepon' => 'nullable',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Simpan ke tabel users
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make('password123'), // default password
            'role' => "Guru"
        ]);
        $user->assignRole('guru');

        // Upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('guru', 'public');
        }

        // Simpan ke tabel guru
        Guru::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'foto' => $fotoPath,
            'user_id' => $user->id,
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit guru
     */
    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    /**
     * Memperbarui data guru & user
     */
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip' => 'required|unique:guru,nip,' . $guru->id,
            'email' => 'required|email|unique:guru,email,' . $guru->id . '|unique:users,email,' . $guru->user_id,
        ]);

        // Update user
        if ($guru->user) {
            $guru->user->update([
                'name' => $request->nama,
                'email' => $request->email,
            ]);
        }

        // Upload foto baru jika ada
        $fotoPath = $guru->foto;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('guru', 'public');
        }

        // Update guru
        $guru->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui');
    }

    /**
     * Menghapus data guru & user terkait
     */
    public function destroy(Guru $guru)
    {
        if ($guru->user) {
            $guru->user->delete();
        }
        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus');
    }
}
