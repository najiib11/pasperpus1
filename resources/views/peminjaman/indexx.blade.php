<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-700 leading-tight">
            {{ __('Daftar Peminjaman Buku') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Alert -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabel -->
        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">#</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nama Peminjam</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Judul Buku</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Jumlah</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Tanggal Pinjam</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Tenggat</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Denda</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
           <tbody class="divide-y divide-gray-200 text-gray-700">
    @forelse($peminjamans as $index => $peminjaman)
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ $index + 1 }}</td>
            <td class="px-4 py-3">{{ $peminjaman->user->name ?? '-' }}</td>
            <td class="px-4 py-3">{{ $peminjaman->buku->judul ?? '-' }}</td>
            <td class="px-4 py-3 text-center">{{ $peminjaman->jumlah }}</td>
            <td class="px-4 py-3 text-center">
                @if($peminjaman->status === 'dipinjam')
                    <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Dipinjam</span>
                @elseif($peminjaman->status === 'dikembalikan')
                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">Dikembalikan</span>
                @elseif($peminjaman->status === 'reservasi')
                    <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">Reservasi</span>
                @endif
            </td>
            <td class="px-4 py-3 text-center">{{ $peminjaman->tanggal_pinjam ?? '-' }}</td>
            <td class="px-4 py-3 text-center">{{ $peminjaman->tenggat ?? '-' }}</td>
            <td class="px-4 py-3 text-center">
                    <span class="text-red-600 font-bold">Rp{{ number_format($peminjaman->denda, 0, ',', '.') }}</span>
            </td>
            <td class="px-4 py-3 text-center">
                <div class="flex justify-center gap-2">
                    {{-- Tombol Edit (jika diperlukan)
                    <a href="{{ route('peminjaman.edit', $peminjaman->id) }}"
                        class="px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-lg">
                        Edit
                    </a>
                    --}}

                    @if($peminjaman->status === 'dipinjam')
                        <form action="{{ route('peminjaman.anggota', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Kembalikan buku ini?')">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-lg">
                                Kembalikan
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg">
                            Hapus
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center py-4 text-gray-500">Belum ada data peminjaman.</td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>
    </div>
</x-app-layout>
