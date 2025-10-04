<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('buku.index') }}" class="text-white hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl text-white dark:text-gray-300 font-semibold">Edit Buku</h1>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Kategori</label>
                            <select name="kategori_id" class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ $buku->kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Judul</label>
                            <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('judul') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Penulis</label>
                            <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('penulis') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Penerbit</label>
                            <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('penerbit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('tahun_terbit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Jumlah Halaman</label>
                            <input type="number" name="jumlah_halaman" value="{{ old('jumlah_halaman', $buku->jumlah_halaman) }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('jumlah_halaman') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Stok</label>
                            <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('stok') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Sumber Pengadaan</label>
                            <select name="sumber_pengadaan"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Sumber --</option>
                                <option value="hibah" {{ $buku->sumber_pengadaan == 'hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="pemerintah" {{ $buku->sumber_pengadaan == 'pemerintah' ? 'selected' : '' }}>Pemerintah</option>
                            </select>
                            @error('sumber_pengadaan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Preview Gambar --}}
                        <div class="mb-3">
                            <label class="block font-medium text-sm text-gray-700">Gambar Buku</label>
                            <input type="file" name="gambar" id="gambar" class="form-control w-full border-gray-300 rounded-md shadow-sm">
                            @error('gambar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                            <div class="mt-3">
                                <img id="preview"
                                     src="{{ $buku->gambar ? asset('storage/'.$buku->gambar) : 'https://via.placeholder.com/150' }}"
                                     class="w-40 h-56 object-contain border rounded-md"
                                     alt="Preview Gambar">
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end gap-2">
                            <a href="{{ route('buku.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview gambar tunggal
        document.getElementById('gambar').addEventListener('change', function (event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
            }
        });
    </script>
</x-app-layout>
