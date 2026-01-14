<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Laporan Reservasi Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form action="{{ route('reservasi.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Tgl Reservasi (Dari)</label>
                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Tgl Reservasi (Sampai)</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-4 flex flex-col gap-2">
                        <div class="flex gap-1">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs w-full">
                                Filter Data
                            </button>
                            <a href="{{ route('reservasi.laporan') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-xs w-full text-center flex items-center justify-center">
                                Reset
                            </a>
                        </div>

                        <div class="flex gap-1">
                            <a href="{{ route('reservasi.download', array_merge(request()->all(), ['jenis' => 'pdf'])) }}" target="_blank"
                               class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs w-full text-center">
                                PDF
                            </a>
                            <a href="{{ route('reservasi.download', array_merge(request()->all(), ['jenis' => 'excel'])) }}"
                               class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs w-full text-center">
                                Excel
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase">
                            <tr>
                                <th class="border px-4 py-2">No</th>
                                <th class="border px-4 py-2">Nama Peminjam</th>
                                <th class="border px-4 py-2">Judul Buku</th>
                                <th class="border px-4 py-2 text-center">Jml</th>
                                <th class="border px-4 py-2 text-center">Tgl Reservasi</th>
                                <th class="border px-4 py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservasis as $r)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $r->user->name ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $r->buku->judul ?? '-' }}</td>
                                <td class="border px-4 py-2 text-center font-bold">{{ $r->jumlah }}</td>
                                <td class="border px-4 py-2 text-center">
                                    {{ $r->tanggal_pinjam ? $r->tanggal_pinjam->format('d/m/Y') : '-' }}
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        Reservasi
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-400">
                                    Tidak ada data reservasi pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-100 font-bold border-t-2 border-gray-300">
                            <tr>
                                <td colspan="3" class="border px-4 py-2 text-right">TOTAL</td>
                                <td class="border px-4 py-2 text-center text-blue-700 text-lg">
                                    {{ $reservasis->sum('jumlah') }}
                                </td>
                                <td colspan="2" class="border px-4 py-2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
