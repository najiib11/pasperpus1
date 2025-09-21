<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('buku.create') }}"
                            class="bg-green-500 text-white px-3 py-2 rounded text-sm hover:bg-green-600 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                            Tambah Buku
                        </a>
                    </div>

                    <table class="table table-bordered w-full">
                        <thead class="bg-blue-800 text-white">
                            <tr>
                                <th class="border px-4 py-2 text-center">ID</th>
                                <th class="border px-4 py-2 text-center">Judul</th>
                                <th class="border px-4 py-2 text-center">Penulis</th>
                                <th class="border px-4 py-2 text-center">Penerbit</th>
                                <th class="border px-4 py-2 text-center">Tahun Terbit</th>
                                <th class="border px-4 py-2 text-center">Jumlah Halaman</th>
                                <th class="border px-4 py-2 text-center">Sumber Pengadaan</th>
                                <th class="border px-4 py-2 text-center">Gambar</th>
                                {{-- <th class="border px-4 py-2 text-center">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buku as $item)
                                <tr>
                                    <td class="border text-center px-4 py-2">{{ $item->id }}</td>
                                    <td class="border text-center px-4 py-2">{{ $item->judul }}</td>
                                    <td class="border text-center px-4 py-2">{{ $item->penulis }}</td>
                                    <td class="border text-center px-4 py-2">{{ $item->penerbit }}</td>
                                    <td class="border text-center px-4 py-2">{{ $item->tahun_terbit }}</td>
                                    <td class="border text-center px-4 py-2">{{ $item->jumlah_halaman }}</td>
                                    <td class="border text-center px-4 py-2">{{ ucfirst($item->sumber_pengadaan) }}</td>
                                    <td class="border text-center px-4 py-2">
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/buku'.$item->gambar) }}" alt="Cover Buku" width="80">
                                        @else
                                            <span class="text-muted">Belum ada gambar</span>
                                        @endif
                                    </td>
                                    {{-- <td class="border px-4 py-2 text-center">
                                        @if($item->stok > 0)
                                            <form action="{{ route('peminjaman.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="buku_id" value="{{ $item->id }}">
                                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                                    Pinjam
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('peminjaman.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="buku_id" value="{{ $item->id }}">
                                                <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                                    Reservasi
                                                </button>
                                            </form>
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
