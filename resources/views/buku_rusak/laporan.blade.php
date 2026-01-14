<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Laporan Buku Rusak & Hilang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- SECTION FILTER --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form action="{{ route('buku-rusak.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                    {{-- 1. Filter Jenis Kerusakan --}}
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Jenis Kerusakan</label>
                        <select name="jenis_kerusakan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="semua">Semua Jenis</option>
                            <option value="Ringan" {{ request('jenis_kerusakan') == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                            <option value="Berat" {{ request('jenis_kerusakan') == 'Berat' ? 'selected' : '' }}>Berat</option>
                            <option value="Hilang" {{ request('jenis_kerusakan') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>

                    {{-- 2. Filter Tanggal Mulai --}}
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    {{-- 3. Filter Tanggal Akhir --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    {{-- 4. Tombol Aksi (Filter, Reset, Download) --}}
                    <div class="md:col-span-4 flex flex-col gap-2">
                        <div class="flex gap-1">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs w-full font-semibold transition">
                                Filter Data
                            </button>
                            <a href="{{ route('buku-rusak.laporan') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-xs w-full text-center flex items-center justify-center font-semibold transition">
                                Reset
                            </a>
                        </div>
                        <div class="flex gap-1">
                            {{-- Tombol PDF --}}
                            <a href="{{ route('buku-rusak.download', array_merge(request()->all(), ['jenis' => 'pdf'])) }}" target="_blank"
                               class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs w-full text-center font-semibold transition flex items-center justify-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                PDF
                            </a>
                            {{-- Tombol Excel --}}
                            <a href="{{ route('buku-rusak.download', array_merge(request()->all(), ['jenis' => 'excel'])) }}"
                               class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs w-full text-center font-semibold transition flex items-center justify-center gap-1">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                               Excel
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- SECTION TABEL DATA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase">
                            <tr>
                                <th class="border px-4 py-2 w-12 text-center">No</th>
                                <th class="border px-4 py-2 w-32">Tanggal</th>
                                <th class="border px-4 py-2">Judul Buku</th>
                                <th class="border px-4 py-2 w-32">Kode Buku</th>
                                <th class="border px-4 py-2">Peminjam</th>
                                <th class="border px-4 py-2 w-24 text-center">Status</th>
                                <th class="border px-4 py-2">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataRusak as $d)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="border px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $d->created_at->format('d/m/Y') }}</td>
                                <td class="border px-4 py-2 font-bold text-gray-800">{{ $d->buku->judul ?? 'Buku Terhapus' }}</td>
                                <td class="border px-4 py-2 text-gray-600">{{ $d->nomor_buku }}</td>
                                <td class="border px-4 py-2">{{ $d->peminjaman->user->name ?? 'User Terhapus' }}</td>
                                <td class="border px-4 py-2 text-center">
                                    @php
                                        // Logika warna badge status
                                        $badgeClass = match($d->jenis_kerusakan) {
                                            'Hilang' => 'bg-red-100 text-red-800 border border-red-200',
                                            'Berat' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                            default => 'bg-yellow-100 text-yellow-800 border border-yellow-200', // Ringan
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ $d->jenis_kerusakan }}
                                    </span>
                                </td>
                                <td class="border px-4 py-2 text-gray-600 italic">
                                    {{ Str::limit($d->catatan, 50) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2 opacity-50">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                        </svg>
                                        Tidak ada data buku rusak sesuai filter yang dipilih.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-100 font-bold border-t-2 border-gray-300">
                            <tr>
                                <td colspan="6" class="border px-4 py-2 text-right text-gray-600">TOTAL KASUS KERUSAKAN</td>
                                <td class="border px-4 py-2 text-center text-red-700 text-lg bg-red-50">
                                    {{ $dataRusak->count() }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
