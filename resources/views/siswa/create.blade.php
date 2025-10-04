<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('siswa.index') }}" class="text-white hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl text-white dark:text-gray-300 font-semibold">Tambah Siswa</h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('siswa.store') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- NISN --}}
                    <div>
                        <label class="block font-medium">NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}"
                               class="w-full border rounded px-3 py-2 @error('nisn') border-red-500 @enderror">
                        @error('nisn') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block font-medium">Nama Siswa</label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               class="w-full border rounded px-3 py-2 @error('nama') border-red-500 @enderror">
                        @error('nama') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jurusan --}}
                    <div>
                        <label class="block font-medium">Jurusan</label>
                        <select name="jurusan" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="Akuntansi">Akuntansi</option>
                            <option value="Perkantoran">Perkantoran</option>
                            <option value="Pemasaran">Pemasaran</option>
                            <option value="Otomotif">Otomotif</option>
                            <option value="Elektronika">Elektronika</option>
                            <option value="Komputer Jaringan">Komputer Jaringan</option>
                        </select>
                        @error('jurusan') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kelas --}}
                    <div>
                        <label class="block font-medium">Kelas</label>
                        <input type="text" name="kelas" placeholder="Contoh: X-1, XI-2, XII-3"
                               value="{{ old('kelas') }}"
                               class="w-full border rounded px-3 py-2 @error('kelas') border-red-500 @enderror">
                        @error('kelas') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block font-medium">Alamat</label>
                        <textarea name="alamat" rows="2"
                                  class="w-full border rounded px-3 py-2">{{ old('alamat') }}</textarea>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block font-medium">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="block font-medium">Nomor Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon') }}"
                               class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- Tempat & Tanggal Lahir --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                   class="w-full border rounded px-3 py-2 @error('tempat_lahir') border-red-500 @enderror">
                            @error('tempat_lahir') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-medium">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                   class="w-full border rounded px-3 py-2 @error('tanggal_lahir') border-red-500 @enderror">
                            @error('tanggal_lahir') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 mt-4">
                        <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Simpan
                        </button>
                        <a href="{{ route('siswa.index') }}"
                           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
