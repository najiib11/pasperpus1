<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();

            $table->string('nama_pengunjung', 150);
            $table->enum('jenis_pengunjung', ['siswa', 'guru'])->default('siswa');

            // Bisa untuk kelas (siswa) atau mapel/jabatan (guru), fleksibel
            $table->string('kelas_mapel', 100)->nullable();

            // waktu kunjungan dicatat (default sekarang)
            $table->dateTime('waktu_kunjungan')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
