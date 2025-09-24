<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'kategori_id',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'jumlah_halaman',
        'stok',
        'sumber_pengadaan',
        'gambar',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
