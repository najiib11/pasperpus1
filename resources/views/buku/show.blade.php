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
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Detail Buku') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    @if($buku->gambar)
                        <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}" class="w-64 h-96 object-cover rounded">
                    @else
                        <div class="w-64 h-96 bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif
                </div>

                <div class="flex-grow space-y-3">
                    <h2 class="text-2xl font-bold">{{ $buku->judul }}</h2>
                    <p><strong>Kategori:</strong> {{ $buku->kategori->nama ?? '-' }}</p>
                    <p><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                    <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                    <p><strong>Tahun Terbit:</strong> {{ $buku->tahun_terbit }}</p>
                    <p><strong>Jumlah Halaman:</strong> {{ $buku->jumlah_halaman }}</p>
                    <p><strong>Stok:</strong> {{ $buku->stok ?? '-' }}</p>
                    <p><strong>Sumber Pengadaan:</strong> {{ ucfirst($buku->sumber_pengadaan) }}</p>

                    <div class="mt-6 {{ in_array(Auth::user()->id_role, [1, 2]) ? '' : 'hidden' }}">
                        <a href="{{ route('buku.edit', $buku->id) }}"
                           class="inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                            Edit Stok Buku
                        </a>
                    </div>

                    <div class="mt-6">
                        {{-- Form Pinjam --}}
                        <form action="{{route('peminjaman.store')}}" method="POST" class="{{in_array(Auth::user()->id_role, [1, 2]) ? 'hidden' : ''}}">
                            @csrf
                            <input type="text" name="user_id" value="{{Auth::user()->id}}" hidden>
                            <input type="text" name="buku_id" value="{{$buku->id}}" hidden>
                            <input type="text" name="jumlah" value="1" hidden>
                            @if(!Auth::user()->hasRole('pustakawan'))
                                <input type="submit" value="Pinjam Buku" class="{{$buku->stok < 1 ? 'hidden' : ''}} inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                            @endif
                        </form>

                        @if (Auth::user()->hasRole('pustakawan'))
                            <div class="flex gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('buku.edit', $buku->id) }}"
                                    class="bg-yellow-500 p-2 rounded-lg text-white hover:bg-yellow-400 transition">
                                    Edit Buku
                                </a>

                                {{-- Tombol Delete --}}
                                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-btn bg-red-600 p-2 rounded-lg text-white hover:bg-red-500 transition">
                                        Hapus Buku
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- Form Reservasi --}}
                        <form action="{{route('reservasi.konfirmasi', $buku->id)}}" method="POST">
                            @csrf
                            <input type="submit" value="Reservasi Buku"
                                class="{{$buku->stok > 0 ? 'hidden' : ''}} inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        </form>

                        {{-- Tombol Kembalikan --}}
                        @php
                            $peminjamanAktif = \App\Models\Peminjaman::where('user_id', Auth::id())
                                ->where('buku_id', $buku->id)
                                ->where('status', 'dipinjam')
                                ->first();
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Tambahkan SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.delete-btn').forEach((button) => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('.delete-form');

                Swal.fire({
                    title: 'Yakin ingin menghapus buku ini?',
                    text: "Data buku akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>
