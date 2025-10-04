<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategoris')->insert([
            ['nama' => 'Novel', 'kode' => 'NVL', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Komik', 'kode' => 'KMK', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ensiklopedia', 'kode' => 'ENS', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pelajaran', 'kode' => 'PLJ', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Majalah', 'kode' => 'MJL', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
