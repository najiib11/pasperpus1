<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungans';

    protected $fillable = [
        'nama_pengunjung',
        'jenis_pengunjung',
        'kelas_mapel',
        'waktu_kunjungan',
    ];

    protected $casts = [
        'waktu_kunjungan' => 'datetime',
    ];
}
