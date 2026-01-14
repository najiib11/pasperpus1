<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-blue-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <h1 class="text-2xl font-bold flex justify-center items-center gap-2">
                        Selamat Datang di
                        <span class="text-blue-600 flex items-center gap-1">
                            PASPERPUS
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m0-12l8 4.5M12 6L4 10.5M20 18V10.5M4 18V10.5m16 7.5l-8-4.5m0 0L4 18"/>
                            </svg>
                        </span>
                    </h1>
                    <p class="mt-2 text-lg text-gray-600">Perpustakaan SMKS Pasundan 1 Cianjur</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-medium uppercase">Total Kunjungan</h3>
                    <p class="text-4xl font-bold text-gray-800 mt-2">{{ number_format($totalKunjungan) }}</p>
                    <p class="text-sm text-gray-400 mt-1">Sejak sistem dibuat</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-medium uppercase">Peminjaman Bulan Ini</h3>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ number_format($totalPeminjamanBulanIni) }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ $currentDate->translatedFormat('F Y') }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-medium uppercase">Total Buku Rusak</h3>
                    <p class="text-4xl font-bold text-red-600 mt-2">{{ number_format($totalBukuRusak) }}</p>
                    <p class="text-sm text-gray-400 mt-1">Akumulasi Kerusakan</p>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Statistik Peminjaman</h3>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('dashboard.pustakawan', ['date' => $prevMonth]) }}"
                               class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </a>
                            <span class="font-semibold text-gray-700 min-w-[120px] text-center">
                                {{ $currentDate->translatedFormat('F Y') }}
                            </span>
                            <a href="{{ route('dashboard.pustakawan', ['date' => $nextMonth]) }}"
                               class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="relative h-72">
                        <canvas id="peminjamanChart"></canvas>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Top 5 Kategori</h3>
                    <div class="relative h-72 flex justify-center">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Chart Peminjaman (Line Chart)
        const ctxPeminjaman = document.getElementById('peminjamanChart').getContext('2d');
        new Chart(ctxPeminjaman, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Jumlah Buku Dipinjam',
                    data: @json($chartData),
                    borderColor: '#2563eb', // Blue-600
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Membuat garis melengkung halus
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });

        // 2. Chart Kategori (Doughnut Chart)
        const ctxKategori = document.getElementById('kategoriChart').getContext('2d');
        new Chart(ctxKategori, {
            type: 'doughnut',
            data: {
                labels: @json($catLabels),
                datasets: [{
                    data: @json($catData),
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</x-app-layout>
