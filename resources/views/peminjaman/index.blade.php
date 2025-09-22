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
                    <div class="mb-4 flex justify-left">
                        <div class="relative w-1/3">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85
                                            3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12
                                            6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </span>
                            <input type="text" id="searchInput"
                                placeholder="Cari peminjaman..."
                                class="border pl-10 pr-3 py-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    </div>
                    <table id="peminjamanTable" class="table-auto w-full border-collapse border border-gray-300">
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

    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let keyword = this.value.toLowerCase();
            let rows = document.querySelectorAll("#peminjamanTable tbody tr");

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(keyword) ? "" : "none";
            });
        });
    </script>
</x-app-layout>
