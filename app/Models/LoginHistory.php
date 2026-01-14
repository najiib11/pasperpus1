<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabel yang sesuai dengan database Anda.
     * Jika tidak didefinisikan, Laravel akan mencari tabel 'login_histories'.
     */
    protected $table = 'login_histories';

    /**
     * Kolom yang boleh diisi secara massal (create/update).
     * Tambahkan baris ini untuk mengatasi error MassAssignmentException.
     */
    protected $fillable = [
        'user_id',
        'login_at',
        'ip_address',
    ];
}
