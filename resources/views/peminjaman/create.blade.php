<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('peminjaman.index') }}" class="text-white hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <!-- Judul header -->
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Tambah Peminjaman') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2">Nama Peminjam</label>
                        <select name="user_id" class="w-full border rounded p-2">
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">Buku</label>
                        <select name="buku_id" id="bukuSelect" class="w-full border rounded p-2" onchange="updateStok()">
                            <option value="">-- Pilih Buku --</option>
                            @foreach($buku as $b)
                                <option value="{{ $b->id }}" data-stok="{{ $b->stok }}">{{ $b->judul }} (Stok: {{ $b->stok }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Field stok buku (readonly) -->
                    <div class="mb-4">
                        <label class="block mb-2">Stok Buku</label>
                        <input type="number" id="stok" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">Jumlah Buku</label>
                        <input type="number" name="jumlah" value="{{ old('jumlah') }}"
                               class="w-full border rounded px-3 py-2 @error('jumlah') border-red-500 @enderror">
                        @error('nisn') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateStok() {
            const select = document.getElementById('bukuSelect');
            const selectedOption = select.options[select.selectedIndex];
            const stok = selectedOption.getAttribute('data-stok');
            document.getElementById('stok').value = stok || '';
        }

        // Auto-trigger saat halaman dimuat jika sebelumnya sudah dipilih
        document.addEventListener('DOMContentLoaded', updateStok);
    </script>

</x-app-layout>
