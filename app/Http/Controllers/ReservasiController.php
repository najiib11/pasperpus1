<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservasiController extends Controller
{
    public function index() {
        $reservasiGrouped = Peminjaman::with(['user', 'buku'])
        ->where('status', 'reservasi')
        ->get()
        ->groupBy('buku_id');

    return view('reservasi.index', compact('reservasiGrouped'));
    }
}
