<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Peminjaman Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('peminjaman.create') }}"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        + Tambah Peminjaman
                        </a>
                    </div>

                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2">Nama Peminjam</th>
                                <th class="border px-4 py-2">Buku</th>
                                <th class="border px-4 py-2">Tanggal Pinjam</th>
                                <th class="border px-4 py-2">Tenggat</th>
                                <th class="border px-4 py-2">Tanggal Kembali</th>
                                <th class="border px-4 py-2">Status</th>
                                <th class="border px-4 py-2">Denda</th>
                                <th class="border px-4 py-2">Aksi</th>
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
                                <td class="border px-4 py-2">
                                    @if($p->status === 'dipinjam')
                                        <form action="{{ route('peminjaman.kembalikan', $p->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button class="bg-blue-500 text-white px-3 py-1 rounded">Kembalikan</button>
                                        </form>
                                    @endif

                                    <a href="{{ route('peminjaman.edit', $p->id) }}"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded ml-2">
                                    Edit
                                    </a>
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
