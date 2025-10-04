<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
     protected $table = 'peminjamans';
    protected $primaryKey = 'id';
     protected $fillable = [
        'user_id', 'buku_id', 'jumlah', 'tanggal_pinjam',
        'tanggal_kembali', 'tenggat', 'denda', 'status'
    ];

    protected $casts = [
        'tanggal_pinjam'    => 'datetime',
        'tenggat'           => 'datetime',
        'tanggal_kembali'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
