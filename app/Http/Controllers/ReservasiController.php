<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;  // INI YANG DIPAKAI, BUKAN Reservasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReservasiController extends Controller
{

    public function index()
    {
        $reservasiGrouped = Peminjaman::with(['user', 'buku'])
            ->where('status', 'reservasi')
            ->get()
            ->groupBy('buku_id');

        return view('reservasi.index', compact('reservasiGrouped'));
    }

    // ======================================
    // KONFIRMASI RESERVASI + KIRIM WHATSAPP
    // ======================================
    public function konfirmasiReservasi($id)
    {
        $reservasi = Peminjaman::findOrFail($id);

        $reservasi->status = 'dipinjam';
        $reservasi->save();

        // Ambil nomor telepon dari siswa atau guru
        $nomorSiswa = optional($reservasi->user->siswa)->telepon;
        $nomorGuru = optional($reservasi->user->guru)->telepon;

        // Pilih nomor yang ada
        $nomor = $nomorSiswa ?: $nomorGuru;

        // Jika dua-duanya tidak ada
        if (!$nomor) {
            return back()->with('error', 'Nomor WhatsApp tidak ditemukan pada data siswa atau guru.');
        }

        // Format pesan
        $pesan = "Halo *{$reservasi->user->name}*,\n\n" .
            "Reservasi bukumu dengan judul *{$reservasi->buku->judul}* sudah *READY*.\n" .
            "Silakan ambil ke perpustakaan. Terima kasih 🙏";

        // Kirim WA
        $this->kirimWhatsapp($nomor, $pesan);

        return back()->with('success', 'Reservasi dikonfirmasi & WhatsApp terkirim!');
    }



    // ======================================
    // FUNGSI KIRIM WHATSAPP API WAPI
    // ======================================
    private function kirimWhatsapp($nomor, $pesan)
    {
        $token = env('WHAPI_TOKEN');
        $baseUrl = env('WHAPI_BASE_URL');

        // Format nomor WA: wajib 62 tanpa 0
        $nomor = preg_replace('/^0/', '62', $nomor);

        Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json',
        ])->post($baseUrl . 'messages/text', [
                    'to' => $nomor,
                    'body' => $pesan,
                ]);
    }

}
