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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // siapa yang pinjam
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade'); // buku mana
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable(); // diisi saat mengembalikan
            $table->date('tenggat'); // max 7 hari
            $table->integer('denda')->default(0);
            $table->string('status')->default('dipinjam');
            // status: dipinjam | dikembalikan | reservasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
