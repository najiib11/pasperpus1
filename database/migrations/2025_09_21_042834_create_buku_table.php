<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('buku', function (Blueprint $table) {
    $table->id();
    $table->string('judul');
    $table->string('penulis');
    $table->string('penerbit');
    $table->year('tahun_terbit');
    $table->integer('jumlah_halaman');
    $table->enum('sumber_pengadaan', ['hibah', 'pemerintah']);
    $table->string('gambar')->nullable(); // tanpa AFTER
    $table->enum('status', ['tersedia', 'dipinjam', 'reservasi'])->default('tersedia');
    $table->integer('stok')->default(0);
    $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
