<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .book-card {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .category-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .gradient-text {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen text-gray-800">

    <!-- Header dengan background pattern -->
    <header class="relative bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.2\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="relative max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center px-6 py-6">
            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                <div class="bg-white rounded-2xl p-3 shadow-lg floating">
                    <i class="fas fa-book-open text-3xl text-blue-600"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Perpustakaan Digital</h1>
                    <p class="text-blue-100 text-sm">SMK Pasundan 1 Cianjur</p>
                </div>
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('login') }}"
                    class="bg-white text-blue-600 hover:bg-blue-50 font-semibold py-3 px-6 rounded-full shadow-lg transition flex items-center space-x-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-16 px-6">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"100\" height=\"100\" viewBox=\"0 0 100 100\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\" fill=\"%23ffffff\" fill-opacity=\"0.4\" fill-rule=\"evenodd\"/%3E%3C/svg%3E');"></div>
        </div>

        <div class="relative max-w-7xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Temukan Buku Favorit Anda</h2>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-10">
                Jelajahi koleksi buku terbaru dan terlengkap di perpustakaan digital kami.
                Tersedia berbagai kategori untuk semua kalangan.
            </p>

            <!-- Search Box -->
          <!-- Search Box -->
<div class="max-w-2xl mx-auto">
    <form action="{{ route('buku.search') }}" method="GET" class="search-box rounded-2xl shadow-xl p-2 flex">
        <input type="text" name="search" placeholder="Cari judul buku, penulis, atau kategori..."
               class="flex-grow px-4 py-3 rounded-xl border-0 focus:ring-2 focus:ring-blue-500 focus:outline-none text-black"
               value="{{ $search ?? '' }}">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition ml-2 flex items-center">
            <i class="fas fa-search mr-2"></i>Cari
        </button>
    </form>

    @if($search)
        <div class="mt-4 text-center">
            <p class="text-white">
                Menampilkan hasil untuk: <strong>"{{ $search }}"</strong>
                <a href="{{ route('buku.tamu') }}" class="ml-2 text-blue-200 hover:text-white">
                    <i class="fas fa-times"></i> Hapus pencarian
                </a>
            </p>
        </div>
    @endif
</div>
        </div>
    </section>

    <!-- Konten Utama -->
    <main class="max-w-7xl mx-auto px-6 py-12">

        <!-- Daftar Buku per Kategori -->
      <!-- Daftar Buku per Kategori -->
<div class="bg-white/90 backdrop-blur-md shadow-xl rounded-2xl p-8">
    @if($search && $kategoris->count() > 0)
        <!-- Tampilkan hasil pencarian -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-search mr-2 text-blue-500"></i>
                Hasil Pencarian: "{{ $search }}"
                <span class="text-blue-600">({{ $bukuResults->flatten()->count() }} hasil ditemukan)</span>
            </h2>
        </div>
    @endif

    @forelse ($kategoris as $kategori)
        <!-- Header Kategori -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="category-badge w-3 h-10 rounded-full"></div>
                    <h2 class="text-3xl font-bold gradient-text">{{ $kategori->nama }}</h2>
                </div>
                <span class="bg-blue-100 text-blue-700 font-semibold py-1 px-4 rounded-full">
                    {{ count($kategori->buku) }} Buku
                </span>
            </div>

            <!-- Grid Buku -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach ($kategori->buku as $buku)
                    <div class="book-card bg-white border border-gray-100 rounded-2xl shadow-md p-4">
                        <!-- Gambar Buku -->
                        <div class="relative mb-4">
                            @if ($buku->gambar)
                                <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}"
                                    class="w-full h-52 object-cover rounded-xl">
                            @else
                                <div class="w-full h-52 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center rounded-xl text-gray-500">
                                    <i class="fas fa-book text-4xl opacity-50"></i>
                                </div>
                            @endif

                            <!-- Highlight search term in title -->
                            @if($search)
                                <div class="absolute top-3 left-3">
                                    <span class="bg-yellow-500 text-white text-xs font-semibold py-1 px-2 rounded-full">
                                        <i class="fas fa-search mr-1"></i> Cocok
                                    </span>
                                </div>
                            @endif

                            <!-- Status Stok -->
                            <div class="absolute top-3 right-3">
                                @if($buku->stok > 5)
                                    <span class="bg-green-500 text-white text-xs font-semibold py-1 px-2 rounded-full">
                                        Tersedia
                                    </span>
                                @elseif($buku->stok > 0)
                                    <span class="bg-yellow-500 text-white text-xs font-semibold py-1 px-2 rounded-full">
                                        Terbatas
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white text-xs font-semibold py-1 px-2 rounded-full">
                                        Habis
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Info Buku -->
                        <div class="px-1">
                            <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2 h-14">
                                @if($search)
                                    {!! highlightText($buku->judul, $search) !!}
                                @else
                                    {{ $buku->judul }}
                                @endif
                            </h3>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-user-edit text-blue-500 mr-2 w-4"></i>
                                    <span class="truncate">
                                        @if($search)
                                            {!! highlightText($buku->penulis, $search) !!}
                                        @else
                                            {{ $buku->penulis }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-building text-blue-500 mr-2 w-4"></i>
                                    <span class="truncate">
                                        @if($search)
                                            {!! highlightText($buku->penerbit, $search) !!}
                                        @else
                                            {{ $buku->penerbit }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-calendar-alt text-blue-500 mr-2 w-4"></i>
                                    <span>{{ $buku->tahun_terbit }}</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                                <div class="text-sm font-medium text-blue-700">
                                    <i class="fas fa-copy mr-1"></i>
                                    Stok: {{ $buku->stok }}
                                </div>
                                {{-- <a href="#"
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg transition flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail
                                </a> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if (!$loop->last)
            <hr class="my-10 border-gray-200">
        @endif
    @empty
        <!-- Jika tidak ada hasil pencarian -->
        <div class="text-center py-16">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-600 mb-2">Tidak ada hasil ditemukan</h3>
            <p class="text-gray-500 mb-6">Coba gunakan kata kunci yang berbeda atau lihat semua koleksi buku kami.</p>
            <a href="{{ route('buku.tamu') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition inline-flex items-center">
                <i class="fas fa-book-open mr-2"></i>
                Lihat Semua Buku
            </a>
        </div>
    @endforelse
</div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-white rounded-xl p-2">
                            <i class="fas fa-book-open text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold">Perpustakaan Digital</h3>
                    </div>
                    <p class="text-gray-400 max-w-md">
                        Menyediakan akses mudah dan cepat ke berbagai koleksi buku untuk mendukung
                        proses belajar mengajar di SMK Pasundan 1 Cianjur.
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak Kami</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-blue-400"></i>
                            <span>Jl. Pendidikan No. 123, Cianjur</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-blue-400"></i>
                            <span>(0263) 123456</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-blue-400"></i>
                            <span>perpustakaan@smkpasundan1.sch.id</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Jam Operasional</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex justify-between">
                            <span>Senin - Jumat</span>
                            <span>07:30 - 16:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Sabtu</span>
                            <span>08:00 - 14:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Minggu</span>
                            <span>Tutup</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Perpustakaan SMK Pasundan 1 Cianjur. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>

</html>
