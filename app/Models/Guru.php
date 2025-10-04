<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'telepon',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'foto',
        'user_id',
    ];

    /**
     * Relasi ke tabel users
     * Setiap guru bisa punya satu user login
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
