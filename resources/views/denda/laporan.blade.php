<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Laporan Denda Keterlambatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form action="{{ route('denda.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Tgl Kembali (Dari)</label>
                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Tgl Kembali (Sampai)</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    </div>

                    <div class="md:col-span-4 flex flex-col gap-2">
                        <div class="flex gap-1">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs w-full">
                                Filter Data
                            </button>
                            <a href="{{ route('denda.laporan') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-xs w-full text-center flex items-center justify-center">
                                Reset
                            </a>
                        </div>
                        <div class="flex gap-1">
                            <a href="{{ route('denda.download', array_merge(request()->all(), ['jenis' => 'pdf'])) }}" target="_blank"
                               class="bg-red-800 hover:bg-red-900 text-white px-2 py-1 rounded text-xs w-full text-center">
                               PDF
                            </a>
                            <a href="{{ route('denda.download', array_merge(request()->all(), ['jenis' => 'excel'])) }}"
                               class="bg-green-700 hover:bg-green-800 text-white px-2 py-1 rounded text-xs w-full text-center">
                               Excel
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-200 text-sm">
                        <thead class="bg-red-100 text-red-900 uppercase">
                            <tr>
                                <th class="border px-4 py-2">No</th>
                                <th class="border px-4 py-2">Nama Peminjam</th>
                                <th class="border px-4 py-2">Judul Buku</th>
                                <th class="border px-4 py-2 text-center">Total Buku</th>
                                <th class="border px-4 py-2 text-center">Tgl Kembali</th>
                                <th class="border px-4 py-2 text-right">Total Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dendas as $d)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2 font-medium">{{ $d->user->name ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $d->buku->judul ?? '-' }}</td>
                                <td class="border px-4 py-2 text-center font-bold">{{ $d->jumlah }}</td>
                                <td class="border px-4 py-2 text-center">
                                    {{ $d->tanggal_kembali ? $d->tanggal_kembali->format('d/m/Y') : '-' }}
                                </td>
                                <td class="border px-4 py-2 text-right font-mono text-red-600 font-bold">
                                    Rp{{ number_format($d->denda, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-400">
                                    Tidak ada data denda pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-red-50 font-bold border-t-2 border-red-200">
                            <tr>
                                <td colspan="3" class="border px-4 py-2 text-right">GRAND TOTAL</td>
                                <td class="border px-4 py-2 text-center text-blue-700 text-lg">
                                    {{ $dendas->sum('jumlah') }}
                                </td>
                                <td class="border px-4 py-2"></td>
                                <td class="border px-4 py-2 text-right text-red-600 text-lg">
                                    Rp{{ number_format($dendas->sum('denda'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
