<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('buku.index') }}" class="text-white hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl text-white dark:text-gray-300 font-semibold">Tambah Buku</h1>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Kategori</label>
                            <select name="kategori_id" class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Judul</label>
                            <input type="text" name="judul" value="{{ old('judul') }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('judul') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Penulis</label>
                            <input type="text" name="penulis" value="{{ old('penulis') }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('penulis') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Penerbit</label>
                            <input type="text" name="penerbit" value="{{ old('penerbit') }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('penerbit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('tahun_terbit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Jumlah Halaman</label>
                            <input type="number" name="jumlah_halaman" value="{{ old('jumlah_halaman') }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('jumlah_halaman') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Stok</label>
                            <input type="number" name="stok" value="{{ old('stok') }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('stok') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Sumber Pengadaan</label>
                            <select name="sumber_pengadaan"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Sumber --</option>
                                <option value="hibah" {{ old('sumber_pengadaan')=='hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="pemerintah" {{ old('sumber_pengadaan')=='pemerintah' ? 'selected' : '' }}>Pemerintah</option>
                            </select>
                            @error('sumber_pengadaan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Gambar Buku</label>
                            <input type="file" name="gambar" class="form-control w-full border-gray-300 rounded-md shadow-sm">
                            @error('gambar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Simpan
                            </button>
                            <a href="{{ route('buku.index') }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
