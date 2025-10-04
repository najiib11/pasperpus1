<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
            'judul','penulis','penerbit','tahun_terbit',
        'jumlah_halaman','sumber_pengadaan','stok',
        'gambar','kategori_id','status'
    ];



    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
