<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-blue-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <a href="{{ route('login') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-md shadow">
            🔑 Masuk
        </a>
    </header>

    <!-- Konten -->
    <main class="p-6">
        <div class="bg-white shadow-lg rounded-xl p-6 w-full">
            @foreach ($kategoris as $kategori)
                <h2 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">{{ $kategori->nama }}</h2>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-10">
                    @forelse ($kategori->buku as $buku)
                        <div class="bg-gray-50 p-3 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                            @if ($buku->gambar)
                                <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}"
                                    class="w-full h-40 object-cover rounded-md mb-2">
                            @else
                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center rounded-md text-gray-400">
                                    No Image
                                </div>
                            @endif

                            <div class="text-sm">
                                <p class="font-semibold text-gray-800 truncate">{{ $buku->judul }}</p>
                                <p class="text-gray-600 text-xs">Penulis: {{ $buku->penulis }}</p>
                                <p class="text-gray-600 text-xs">Penerbit: {{ $buku->penerbit }}</p>
                                <p class="text-gray-600 text-xs">Tahun: {{ $buku->tahun_terbit }}</p>
                                <p class="text-gray-700 text-xs mt-1">Stok: {{ $buku->stok }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Belum ada buku dalam kategori ini.</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    </main>

</body>

</html>
