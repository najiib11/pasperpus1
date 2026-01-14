<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuRusak extends Model
{
    use HasFactory;

    protected $table = 'buku_rusaks'; // Opsional, untuk memastikan nama tabel

    protected $fillable = [
        'peminjaman_id',
        'buku_id',
        'nomor_buku',
        'jenis_kerusakan',
        'catatan'
    ];

    /**
     * Relasi ke Model Buku
     * (Mengatasi error: Call to undefined relationship [buku])
     */
    public function buku()
    {
        // Pastikan 'Buku::class' sesuai dengan nama Model Buku Anda (misal App\Models\Buku)
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /**
     * Relasi ke Model Peminjaman
     * (Diperlukan karena di controller kita memanggil 'peminjaman.user')
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
}
