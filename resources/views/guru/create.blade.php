<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('guru.index') }}" class="text-white hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl text-white dark:text-gray-300 font-semibold">Tambah Guru</h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('guru.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    {{-- NIP --}}
                    <div>
                        <label class="block font-medium">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip') }}"
                               class="w-full border rounded px-3 py-2 @error('nip') border-red-500 @enderror">
                        @error('nip') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block font-medium">Nama Guru</label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               class="w-full border rounded px-3 py-2 @error('nama') border-red-500 @enderror">
                        @error('nama') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block font-medium">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full border rounded px-3 py-2 @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                               class="w-full border rounded px-3 py-2 @error('telepon') border-red-500 @enderror">
                        @error('telepon') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
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

                    {{-- Alamat --}}
                    <div>
                        <label class="block font-medium">Alamat</label>
                        <textarea name="alamat" rows="2"
                                  class="w-full border rounded px-3 py-2 @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    {{-- Upload Foto --}}
                    <div>
                        <label class="block font-medium">Foto</label>
                        <input type="file" name="foto" id="foto" accept="image/*"
                               class="w-full border rounded px-3 py-2 @error('foto') border-red-500 @enderror">
                        @error('foto') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror

                        <div class="mt-3">
                            <img id="preview" src="#" alt="Preview Foto" class="hidden w-32 h-32 object-cover rounded border shadow">
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 mt-4">
                        <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Simpan
                        </button>
                        <a href="{{ route('guru.index') }}"
                           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Preview Foto Otomatis --}}
    <script>
        document.getElementById('foto').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
