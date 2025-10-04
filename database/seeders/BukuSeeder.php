<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bukus = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'jumlah_halaman' => 529,
                'sumber_pengadaan' => 'hibah',
                'stok' => 10,
                'kategori_id' => 1, // asumsi Novel
                'gambar' => null,
            ],
            [
                'judul' => 'Naruto Vol. 1',
                'penulis' => 'Masashi Kishimoto',
                'penerbit' => 'Shueisha',
                'tahun_terbit' => 1999,
                'jumlah_halaman' => 190,
                'sumber_pengadaan' => 'pemerintah',
                'stok' => 5,
                'kategori_id' => 2, // asumsi Komik
                'gambar' => null,
            ],
            [
                'judul' => 'Matematika SMA Kelas 10',
                'penulis' => 'Tim Penulis Erlangga',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2020,
                'jumlah_halaman' => 250,
                'sumber_pengadaan' => 'pemerintah',
                'stok' => 15,
                'kategori_id' => 3, // asumsi Pelajaran
                'gambar' => null,
            ],
        ];

        foreach ($bukus as $buku) {
            Buku::create($buku);
        }
    }
}
