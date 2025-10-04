<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Pustakawan',
            'email' => 'pustakawan@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'pustakawan'
        ])->assignRole('pustakawan');

        // User Guru
        $guruUser = User::create([
            'name' => 'Guru',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'guru'
        ])->assignRole('guru');

        // Buat data Guru terkait
        Guru::create([
            'user_id' => $guruUser->id,
            'nama'    => 'Budi Santoso',
            'nip'     => '1987654321',
            'email'   => 'guru@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1980-05-10',
            'alamat' => 'Jl. Merdeka No. 10',
            'telepon' => '081234567890'
        ]);

        // User Anggota 1
        $siswaUser1 = User::create([
            'name' => 'User Anggota 1',
            'email' => 'anggota@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'anggota'
        ])->assignRole('anggota');

        // Buat data Siswa terkait
        Siswa::create([
            'user_id' => $siswaUser1->id,
            'nisn'    => '1234567890',
            'nama'    => 'Andi Wijaya',
            'jurusan' => 'Akuntansi',
            'kelas'   => 'X-1',
            'email'   => 'anggota@gmail.com',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2005-03-15',
            'alamat' => 'Jl. Sudirman No. 5',
            'telepon' => '081234567891'
        ]);

        // User Anggota 2
        $siswaUser2 = User::create([
            'name' => 'User Anggota 2',
            'email' => 'anggota2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'anggota'
        ])->assignRole('anggota');

        // Buat data Siswa terkait
        Siswa::create([
            'user_id' => $siswaUser2->id,
            'nisn'    => '0987654321',
            'nama'    => 'Siti Nurhaliza',
            'jurusan' => 'Perkantoran',
            'kelas'   => 'XI-2',
            'email'   => 'anggota2@gmail.com',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2004-07-20',
            'alamat' => 'Jl. Pahlawan No. 7',
            'telepon' => '081234567892'
        ]);
    }
}
