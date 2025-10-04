<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nisn',
        'nama',
        'jurusan',
        'kelas',
        'alamat',
        'jenis_kelamin',
        'email',
        'telepon',
        'tanggal_lahir',
        'tempat_lahir',
        'user_id'
    ];
}
