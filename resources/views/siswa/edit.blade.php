<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <!-- Tombol kembali -->
            <a href="{{ route('siswa.index') }}" class="text-white hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Edit Data Siswa
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- NISN -->
                        <div>
                            <label class="block font-semibold">NISN</label>
                            <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}"
                                   class="w-full border rounded px-3 py-2">
                            @error('nisn') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama -->
                        <div>
                            <label class="block font-semibold">Nama</label>
                            <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}"
                                   class="w-full border rounded px-3 py-2">
                            @error('nama') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jurusan -->
                        <div>
                            <label class="block font-semibold">Jurusan</label>
                            <select name="jurusan" class="w-full border rounded px-3 py-2">
                                @php
                                    $jurusanList = ['Akuntansi', 'Perkantoran', 'Pemasaran', 'Otomotif', 'Elektronika', 'Komputer Jaringan'];
                                @endphp
                                @foreach($jurusanList as $j)
                                    <option value="{{ $j }}" {{ old('jurusan', $siswa->jurusan) == $j ? 'selected' : '' }}>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label class="block font-semibold">Kelas</label>
                            <input type="text" name="kelas" value="{{ old('kelas', $siswa->kelas) }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block font-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full border rounded px-3 py-2">
                                <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label class="block font-semibold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block font-semibold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block font-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email', $siswa->email) }}"
                                   class="w-full border rounded px-3 py-2">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label class="block font-semibold">Nomor Telepon</label>
                            <input type="text" name="telepon" value="{{ old('telepon', $siswa->telepon) }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mt-4">
                        <label class="block font-semibold">Alamat</label>
                        <textarea name="alamat" rows="3" class="w-full border rounded px-3 py-2">{{ old('alamat', $siswa->alamat) }}</textarea>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan Perubahan
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
