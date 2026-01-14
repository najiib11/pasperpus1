<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangani Tanggal
        $currentDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $prevMonth = $currentDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentDate->copy()->addMonth()->format('Y-m');

        // 2. Total Kunjungan
        $totalKunjungan = DB::table('login_histories')->count(); // Sesuaikan nama tabel jika Anda pakai 'kunjungans'

        // 3. Data Peminjaman Bulan Ini
        $peminjamanHarian = DB::table('peminjamans')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereMonth('created_at', $currentDate->month)
            ->whereYear('created_at', $currentDate->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format Chart ... (Kode chart tetap sama) ...
        $daysInMonth = $currentDate->daysInMonth;
        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateLoop = $currentDate->copy()->day($i)->format('Y-m-d');
            $chartLabels[] = $i;
            $dataHariIni = $peminjamanHarian->firstWhere('date', $dateLoop);
            $chartData[] = $dataHariIni ? $dataHariIni->count : 0;
        }
        $totalPeminjamanBulanIni = array_sum($chartData);

        // 4. Top 5 Kategori
        $topCategories = DB::table('peminjamans')
            ->join('buku', 'peminjamans.buku_id', '=', 'buku.id')
            ->join('kategoris', 'buku.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.nama', DB::raw('count(*) as total'))
            ->groupBy('kategoris.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $catLabels = $topCategories->pluck('nama');
        $catData = $topCategories->pluck('total');

        // --- TAMBAHAN BARU: Total Buku Rusak ---
        $totalBukuRusak = DB::table('buku_rusaks')->count();

        return view('dashboard', compact(
            'totalKunjungan',
            'currentDate',
            'prevMonth',
            'nextMonth',
            'chartLabels',
            'chartData',
            'totalPeminjamanBulanIni',
            'catLabels',
            'catData',
            'totalBukuRusak' // <--- Masukkan variabel baru ini
        ));
    }
}
