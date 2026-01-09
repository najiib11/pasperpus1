<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Daftar Buku Rusak') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter & Tombol Cetak --}}
            <div class="bg-white p-4 rounded-lg shadow mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <form action="{{ route('buku-rusak.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="border rounded px-2 py-1 text-sm">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="border rounded px-2 py-1 text-sm">
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Filter</button>
                    <a href="{{ route('buku-rusak.index') }}" class="text-gray-500 text-sm hover:underline ml-2">Reset</a>
                </form>

                <a href="{{ route('buku-rusak.cetak', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                   target="_blank"
                   class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 hover:bg-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l-2.292-8.565m0 0a42.41 42.41 0 01-8.736 0" />
                    </svg>
                    Cetak Laporan
                </a>
            </div>

            {{-- Tabel Data --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                                <th class="py-3 px-6">Tanggal Lapor</th>
                                <th class="py-3 px-6">Judul Buku</th>
                                <th class="py-3 px-6">Kode Buku</th>
                                <th class="py-3 px-6">Peminjam</th>
                                <th class="py-3 px-6">Kerusakan</th>
                                <th class="py-3 px-6">Catatan</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($dataRusak as $d)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6">{{ $d->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-6 font-medium">{{ $d->buku->judul ?? 'Buku Terhapus' }}</td>
                                <td class="py-3 px-6">{{ $d->nomor_buku }}</td>
                                <td class="py-3 px-6">{{ $d->peminjaman->user->name ?? 'User Terhapus' }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 rounded text-xs text-white
                                        {{ $d->jenis_kerusakan == 'Hilang' ? 'bg-red-500' : ($d->jenis_kerusakan == 'Berat' ? 'bg-orange-500' : 'bg-yellow-500') }}">
                                        {{ $d->jenis_kerusakan }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">{{ Str::limit($d->catatan, 30) }}</td>
                                <td class="py-3 px-6 text-center flex justify-center gap-2">
                                    <a href="{{ route('buku-rusak.edit', $d->id) }}" class="text-blue-500 hover:text-blue-700">
                                        Edit
                                    </a>
                                    <form action="{{ route('buku-rusak.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Hapus data kerusakan? Stok buku akan dikembalikan (+1).')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-400">Belum ada data buku rusak</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $dataRusak->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
