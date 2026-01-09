<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Laporan Data Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form action="{{ route('buku.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="kategori_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="semua">Semua Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Sumber</label>
                        <select name="sumber_pengadaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="semua">Semua Sumber</option>
                            <option value="hibah" {{ request('sumber_pengadaan') == 'hibah' ? 'selected' : '' }}>Hibah</option>
                            <option value="pemerintah" {{ request('sumber_pengadaan') == 'pemerintah' ? 'selected' : '' }}>Pemerintah</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" placeholder="Cth: 2024" value="{{ request('tahun_terbit') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-4 flex flex-col gap-2">
                        <div class="flex gap-1">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs w-full">
                                Filter
                            </button>
                            <a href="{{ route('buku.laporan') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-xs w-full text-center flex items-center justify-center">
                                Reset
                            </a>
                        </div>
                        <div class="flex gap-1">
                            <a href="{{ route('buku.download', array_merge(request()->all(), ['jenis' => 'pdf'])) }}" target="_blank"
                               class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs w-full text-center">
                               PDF
                            </a>
                            <a href="{{ route('buku.download', array_merge(request()->all(), ['jenis' => 'excel'])) }}"
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
                                <th class="border px-4 py-2">Judul</th>
                                <th class="border px-4 py-2">Penulis</th>
                                <th class="border px-4 py-2">Penerbit</th>
                                <th class="border px-4 py-2 text-center">Tahun</th>
                                <th class="border px-4 py-2">Kategori</th>
                                <th class="border px-4 py-2">Sumber</th>
                                <th class="border px-4 py-2 text-center">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukus as $b)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2 font-bold text-gray-800">{{ $b->judul }}</td>
                                <td class="border px-4 py-2">{{ $b->penulis }}</td>
                                <td class="border px-4 py-2">{{ $b->penerbit }}</td>
                                <td class="border px-4 py-2 text-center">{{ $b->tahun_terbit }}</td>
                                <td class="border px-4 py-2">{{ $b->kategori->nama ?? '-' }}</td>
                                <td class="border px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs {{ $b->sumber_pengadaan == 'pemerintah' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ ucfirst($b->sumber_pengadaan) }}
                                    </span>
                                </td>
                                <td class="border px-4 py-2 text-center font-bold {{ $b->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $b->stok }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-gray-400">
                                    Tidak ada data buku sesuai filter.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-100 font-bold border-t-2 border-gray-300">
                            <tr>
                                <td colspan="7" class="border px-4 py-2 text-right">TOTAL STOK BUKU</td>
                                <td class="border px-4 py-2 text-center text-blue-700 text-lg">
                                    {{ $bukus->sum('stok') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
