<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Daftar Kategori') }}
            </h2>
            <a href="{{ route('kategori.create') }}"
               class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 text-sm">
                + Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">

            {{-- ✅ ALERT SUCCESS --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- ⚠️ ALERT ERROR --}}
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- ⚠️ ALERT VALIDASI ERROR (opsional) --}}
            @if ($errors->any())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Perhatian!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="w-full border">
                <thead class="bg-blue-400 dark:bg-gray-700 text-white">
                    <tr>
                        <th class="p-2 border">No</th>
                        <th class="p-2 border">Kode</th>
                        <th class="p-2 border">Nama</th>
                        <th class="p-2 border w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategoris as $kategori)
                        <tr>
                            <td class="border p-2 text-center">{{ $loop->iteration }}</td>
                            <td class="border p-2">{{ $kategori->kode }}</td>
                            <td class="border p-2">{{ $kategori->nama }}</td>
                            <td class="border p-2 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('kategori.edit', $kategori->id) }}"
                                       class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0
                                                .706L14.459 3.69l-2-2
                                                1.043-1.043a.5.5 0 0 1
                                                .707 0l1.293 1.293zm-1.75
                                                2.456-2-2L4.939 9.21a.5.5
                                                0 0 0-.121.196l-.805
                                                2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5
                                                0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0
                                                2.5 15h11a1.5 1.5 0 0 0
                                                1.5-1.5v-6a.5.5 0 0 0-1
                                                0v6a.5.5 0 0 1-.5.5h-11a.5.5
                                                0 0 1-.5-.5v-11a.5.5 0 0 1
                                                .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5
                                                1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                        Ubah
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 flex items-center gap-1"
                                            onclick="return confirm('Hapus kategori ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6
                                                    6v6a.5.5 0 0 1-1
                                                    0V6a.5.5 0 0 1
                                                    .5-.5m2.5 0a.5.5 0 0 1
                                                    .5.5v6a.5.5 0 0 1-1
                                                    0V6a.5.5 0 0 1
                                                    .5-.5m3 .5a.5.5 0 0 0-1
                                                    0v6a.5.5 0 0 0 1 0z"/>
                                                <path d="M14.5 3a1 1 0 0 1-1
                                                    1H13v9a2 2 0 0 1-2
                                                    2H5a2 2 0 0 1-2-2V4h-.5a1
                                                    1 0 0 1-1-1V2a1 1 0 0 1
                                                    1-1H6a1 1 0 0 1 1-1h2a1
                                                    1 0 0 1 1 1h3.5a1 1 0 0 1
                                                    1 1zM4.118 4 4
                                                    4.059V13a1 1 0 0 0 1
                                                    1h6a1 1 0 0 0 1-1V4.059L11.882
                                                    4zM2.5 3h11V2h-11z"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
