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
                {{-- Gambar Buku --}}
                <div class="flex-shrink-0">
                    @if($buku->gambar)
                        <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}" class="w-64 h-96 object-cover rounded">
                    @else
                        <div class="w-64 h-96 bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif
                </div>

                {{-- Detail Buku --}}
                <div class="flex-grow space-y-3">
                    <h2 class="text-2xl font-bold">{{ $buku->judul }}</h2>
                    <p><strong>Kategori:</strong> {{ $buku->kategori->nama ?? '-' }}</p>
                    <p><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                    <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                    <p><strong>Tahun Terbit:</strong> {{ $buku->tahun_terbit }}</p>
                    <p><strong>Jumlah Halaman:</strong> {{ $buku->jumlah_halaman }}</p>
                    <p><strong>Stok:</strong> {{ $buku->stok ?? '-' }}</p>
                    <p><strong>Sumber Pengadaan:</strong> {{ ucfirst($buku->sumber_pengadaan) }}</p>

                    {{-- Tombol Edit untuk Pustakawan --}}
                    @if (Auth::user()->hasRole('pustakawan'))
                        <div class="mt-6 flex gap-2">
                            <a href="{{ route('buku.edit', $buku->id) }}" class="bg-yellow-500 p-2 rounded-lg text-white hover:bg-yellow-400 transition">
                                Edit Buku
                            </a>
                            <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="delete-form inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-btn bg-red-600 p-2 rounded-lg text-white hover:bg-red-500 transition">
                                    Hapus Buku
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Tombol Reservasi --}}
                    <form action="{{ route('reservasi.konfirmasi', $buku->id) }}" method="POST" class="mt-4">
                        @csrf
                        <input type="submit" value="Reservasi Buku"
                            class="{{ $buku->stok > 0 ? 'hidden' : '' }} inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    </form>

                    {{-- Form Pinjam --}}
                    @if(!Auth::user()->hasRole('pustakawan'))
                        @if(Auth::user()->hasRole('guru'))
                            {{-- Tombol Guru --}}
                            <button type="button"
                                    class="{{ $buku->stok < 1 ? 'hidden' : '' }} inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mt-4"
                                    onclick="openModal()">
                                Pinjam Buku
                            </button>

                            {{-- Modal Pinjam Guru --}}
                            <div id="pinjamModal" class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50">
                                <div class="bg-white rounded-lg shadow-lg p-6 w-80">
                                    <h2 class="text-lg font-bold mb-4">Pinjam Buku</h2>
                                    <form action="{{ route('peminjaman.store') }}" method="POST">
                                        @csrf
                                        <input type="text" name="user_id" value="{{ Auth::user()->id }}" hidden>
                                        <input type="text" name="buku_id" value="{{ $buku->id }}" hidden>

                                        <div class="mb-4">
                                            <label class="block font-medium mb-1">Jumlah Buku</label>
                                            <input type="number" name="jumlah" min="1" max="{{ $buku->stok }}" value="1"
                                                   class="w-full border rounded px-3 py-2">
                                            <p class="text-sm text-gray-500 mt-1">Maksimal {{ $buku->stok }} buku</p>
                                        </div>

                                        <div class="flex justify-end gap-2">
                                            <button type="button" class="px-4 py-2 bg-gray-400 rounded hover:bg-gray-500" onclick="closeModal()">Batal</button>
                                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Pinjam</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <script>
                                function openModal() {
                                    document.getElementById('pinjamModal').classList.remove('hidden');
                                }
                                function closeModal() {
                                    document.getElementById('pinjamModal').classList.add('hidden');
                                }
                            </script>
                        @else
                            {{-- Form Pinjam Anggota --}}
                            <form action="{{ route('peminjaman.store') }}" method="POST" class="{{ $buku->stok < 1 ? 'hidden' : '' }} mt-4">
                                @csrf
                                <input type="text" name="user_id" value="{{ Auth::user()->id }}" hidden>
                                <input type="text" name="buku_id" value="{{ $buku->id }}" hidden>
                                <input type="text" name="jumlah" value="1" hidden>
                                <input type="submit" value="Pinjam Buku"
                                       class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
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
