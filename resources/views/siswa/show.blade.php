<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('siswa.index') }}" class="text-white hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Kartu Anggota Siswa
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-md mx-auto">
            <div class="bg-white shadow-lg rounded-lg border overflow-hidden">
                <!-- Header kartu -->
                <div class="bg-blue-600 text-white p-4 text-center">
                    <h1 class="text-lg font-bold">KARTU ANGGOTA PERPUSTAKAAN</h1>
                    <p class="text-sm">SMKS PASUNDAN 1 CIANJUR</p>
                </div>

                <!-- Isi kartu -->
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <!-- Foto profil default -->
                        <div class="w-24 h-24 bg-gray-200 flex items-center justify-center rounded">
                            <span class="text-gray-500 text-xs">Foto</span>
                        </div>
                        <div>
                            <h2 class="font-bold text-lg">{{ $siswa->nama }}</h2>
                            <p class="text-sm text-gray-600">NISN : {{ $siswa->nisn }}</p>
                            <p class="text-sm text-gray-600">Jurusan : {{ $siswa->jurusan }}</p>
                            <p class="text-sm text-gray-600">Kelas : {{ $siswa->kelas }}</p>
                        </div>
                    </div>

                    <div class="flex flex-row items-center justify-between">
                        <div class="mt-4 w-[50%]">
                            <p class="text-sm mb-2"><strong>Jenis Kelamin :</strong> {{ $siswa->jenis_kelamin }}</p>
                            <p class="text-sm mb-2"><strong>Email :</strong> {{ $siswa->email }}</p>
                            <p class="text-sm mb-2"><strong>Telepon :</strong> {{ $siswa->telepon }}</p>
                            <p class="text-sm mb-2"><strong>TTL :</strong> {{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir }}</p>
                            <p class="text-sm mb-2"><strong>Alamat :</strong> {{ $siswa->alamat }}</p>
                        </div>
                        <div class="barcode text-center">
                            {!! $qrCodeSvg !!}
                            <p style="font-size:10px;">{{ $siswa->nisn }}</p>
                        </div>
                    </div>
                </div>

                <!-- Footer kartu -->
                <div class="bg-gray-100 p-3 text-center">
                    <p class="text-xs text-gray-600">Kartu ini sah tanpa tanda tangan</p>
                </div>
            </div>

            {{-- Tombol aksi hanya muncul di browser, tidak ikut ke PDF --}}
            @if(!app()->runningInConsole() && !request()->routeIs('siswa.cetak'))
                <div class="mt-6 flex justify-center gap-3">
                    <a href="{{ route('siswa.edit', $siswa->id) }}"
                       class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Edit Data
                    </a>
                    <a href="{{ route('siswa.cetak', $siswa->id) }}"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Cetak Kartu
                    </a>
                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST"
                          onsubmit="return confirm('Apakah yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
