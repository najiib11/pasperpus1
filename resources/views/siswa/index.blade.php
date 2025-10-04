<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Data Siswa') }}
            </h2>
            <a href="{{ route('siswa.create') }}"
               class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 text-sm">
                + Tambah Siswa
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="mb-4 flex justify-left">
                    <div class="relative w-1/3">
                        <!-- Ikon search -->
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1
                                        1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12
                                        6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                        </span>

                        <!-- Input -->
                        <input type="text" id="searchInput"
                            placeholder="Cari siswa..."
                            class="border pl-10 pr-3 py-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
                <table id="siswaTable" class="w-full border-collapse border">
                    <thead class="bg-blue-400 dark:bg-gray-700 text-white">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-center">NISN</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Nama</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Jurusan</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Kelas</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Email</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Jenis Kelamin</th>
                            <th class="p-2 border w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $item)
                            <tr>
                                <td class="border px-3 py-2 text-center">{{ $item->nisn }}</td>
                                <td class="border px-3 py-2">{{ $item->nama }}</td>
                                <td class="border px-3 py-2 text-center">{{ $item->jurusan }}</td>
                                <td class="border px-3 py-2 text-center">{{ $item->kelas }}</td>
                                <td class="border px-3 py-2">{{ $item->email }}</td>
                                <td class="border px-3 py-2 text-center">{{ $item->jenis_kelamin }}</td>
                                <td class="border justify-center px-3 py-2 flex gap-2">
                                    <a href="{{ route('siswa.show', $item->id) }}"
                                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm flex items-center gap-1 hover:bg-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 14a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 14h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Kartu
                                    </a>
                                    <form action="{{ route('siswa.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus siswa ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 text-white px-3 py-1 rounded text-sm flex items-center gap-1 hover:bg-red-600">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let keyword = this.value.toLowerCase();
            let rows = document.querySelectorAll("#siswaTable tbody tr");

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(keyword) ? "" : "none";
            });
        });
    </script>
</x-app-layout>
