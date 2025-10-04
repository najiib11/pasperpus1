<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Data Guru') }}
            </h2>
            <a href="{{ route('guru.create') }}"
               class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 text-sm">
                + Tambah Guru
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <!-- Search -->
                <div class="mb-4 flex justify-left">
                    <div class="relative w-1/3">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                 fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1
                                        1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12
                                        6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                        </span>

                        <input type="text" id="searchInput"
                               placeholder="Cari guru..."
                               class="border pl-10 pr-3 py-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>

                <!-- Table -->
                <table id="guruTable" class="w-full border-collapse border">
                    <thead class="bg-blue-400 text-white">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-center">NIP</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Nama</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Email</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Telepon</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Jenis Kelamin</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Tempat Lahir</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Tanggal Lahir</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Alamat</th>
                            <th class="p-2 border w-24 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guru as $item)
                            <tr>
                                <td class="border px-3 py-2 text-center">{{ $item->nip }}</td>
                                <td class="border px-3 py-2">{{ $item->nama }}</td>
                                <td class="border px-3 py-2">{{ $item->email }}</td>
                                <td class="border px-3 py-2 text-center">{{ $item->telepon ?? '-' }}</td>
                                <td class="border px-3 py-2 text-center">{{ $item->jenis_kelamin }}</td>
                                <td class="border px-3 py-2 text-center">{{ $item->tempat_lahir }}</td>
                                <td class="border px-3 py-2 text-center">{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                                <td class="border px-3 py-2">{{ $item->alamat }}</td>

                                <td class="border justify-center px-3 py-2 flex gap-2">
                                    <a href="{{ route('guru.edit', $item->id) }}"
                                       class="bg-yellow-500 text-white px-3 py-1 rounded text-sm flex items-center gap-1 hover:bg-yellow-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15.232 5.232l3.536 3.536M9 13l6-6m-6 6l-1.5 6L13 19l6-6" />
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('guru.destroy', $item->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="delete-btn bg-red-500 text-white px-3 py-1 rounded text-sm flex items-center gap-1 hover:bg-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1
                                                        0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1
                                                        .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1
                                                        .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0
                                                        1 0z"/>
                                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2
                                                        2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1
                                                        1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1
                                                        1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4
                                                        4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0
                                                        1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-gray-500">
                                    Tidak Ada Data Guru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Pencarian guru
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let keyword = this.value.toLowerCase();
            let rows = document.querySelectorAll("#guruTable tbody tr");

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(keyword) ? "" : "none";
            });
        });

        // SweetAlert konfirmasi hapus
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Yakin hapus data ini?',
                    text: "Data guru akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>
