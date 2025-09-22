<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Daftar Peminjaman Buku') }}
            </h2>
            <a href="{{ route('peminjaman.create') }}"
            class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 text-sm">
                + Tambah Peminjaman
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead class="bg-blue-400 dark:bg-gray-700 text-white">
                            <tr>
                                <th class="border px-4 py-2">Nama Peminjam</th>
                                <th class="border px-4 py-2">Judul Buku</th>
                                <th class="border px-4 py-2">Tanggal Pinjam</th>
                                <th class="border px-4 py-2">Tenggat</th>
                                <th class="border px-4 py-2">Tanggal Kembali</th>
                                <th class="border px-4 py-2">Status</th>
                                <th class="border px-4 py-2">Denda</th>
                                <th class="p-2 border w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamans as $p)
                            <tr>
                                <td class="border px-4 py-2">{{ $p->user->name }}</td>
                                <td class="border px-4 py-2">{{ $p->buku->judul }}</td>
                                <td class="border px-4 py-2">{{ $p->tanggal_pinjam }}</td>
                                <td class="border px-4 py-2">{{ $p->tenggat }}</td>
                                <td class="border px-4 py-2">{{ $p->tanggal_kembali ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($p->status) }}</td>
                                <td class="border px-4 py-2">Rp {{ number_format($p->denda,0,',','.') }}</td>
                                <td class="justify-center px-3 py-2 flex gap-2">
                                    @if($p->status === 'dipinjam')
                                        <form action="{{ route('peminjaman.kembalikan', $p->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button class="bg-blue-500 text-white px-3 py-1 rounded">Kembalikan</button>
                                        </form>

                                        <a href="{{ route('peminjaman.edit', $p->id) }}"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded ml-2">
                                        Edit
                                        </a>
                                    @elseif($p->status === 'reservasi')
                                        <a href="{{ route('peminjaman.edit', $p->id) }}"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded ml-2">
                                        Edit
                                        </a>
                                    @else
                                        <button class="bg-gray-400 text-white px-3 py-1 rounded ml-2 cursor-not-allowed" disabled>
                                            Edit
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
