<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Edit Data Peminjaman</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST" id="formPeminjaman">
                    @csrf
                    @method('PUT')

                    {{-- Data Readonly (Tidak berubah) --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nama Peminjam</label>
                            <input type="text" value="{{ $peminjaman->user->name }}" class="w-full border-gray-300 rounded bg-gray-100 text-gray-500" readonly>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Judul Buku</label>
                            <input type="text" value="{{ $peminjaman->buku->judul ?? '-' }}" class="w-full border-gray-300 rounded bg-gray-100 text-gray-500" readonly>
                            {{-- Hidden Inputs --}}
                            <input type="hidden" name="user_id" value="{{ $peminjaman->user_id }}">
                            <input type="hidden" name="buku_id" value="{{ $peminjaman->buku_id }}">
                            <input type="hidden" name="jumlah" value="{{ $peminjaman->jumlah }}">
                            <input type="hidden" name="tanggal_pinjam" value="{{ $peminjaman->tanggal_pinjam }}">
                            <input type="hidden" name="tenggat" value="{{ $peminjaman->tenggat }}">
                        </div>
                    </div>

                    {{-- Status Dropdown --}}
                    <div class="mb-6 border-b pb-6">
                        <label class="block font-medium text-lg mb-2">Status Peminjaman</label>
                        <select name="status" class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                            <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Di Kembalikan</option>
                        </select>
                    </div>

                    {{-- === AREA BUKU RUSAK === --}}
                    <div class="mb-6 bg-red-50 p-4 rounded-lg border border-red-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-red-700 font-bold text-lg">Lapor Buku Rusak</h3>
                            <span class="text-sm text-gray-500">
                                Maksimal lapor: <span id="sisaBuku">{{ $peminjaman->jumlah }}</span> buku
                            </span>
                        </div>

                        <div id="container-rusak" class="space-y-4">
                            </div>

                        <button type="button" id="btnTambahRusak" onclick="tambahFormRusak()"
                                class="mt-3 bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Data Kerusakan
                        </button>
                    </div>
                    {{-- === END AREA BUKU RUSAK === --}}

                    <div class="flex justify-end pt-4">
                        <a href="{{ route('peminjaman.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script Javascript --}}
    <script>
        // Mengambil data dari PHP
        const maxBuku = {{ $peminjaman->jumlah }};
        const namaBuku = "{{ $peminjaman->buku->judul ?? 'Buku Tidak Ditemukan' }}";
        // Asumsi relasi buku ke kategori ada. Jika error, ganti $peminjaman->buku->kategori->nama jadi string kosong dulu
        const kategoriBuku = "{{ $peminjaman->buku->kategori->nama ?? 'Umum' }}";

        let counterRusak = 0;

        function tambahFormRusak() {
            if (counterRusak >= maxBuku) {
                alert('Jumlah buku rusak tidak boleh melebihi total buku yang dipinjam (' + maxBuku + ')!');
                return;
            }

            counterRusak++;
            updateCounterUI();

            const html = `
                <div class="rusak-item border border-red-300 bg-white p-4 rounded shadow-sm relative" id="row-rusak-${counterRusak}">
                    <button type="button" onclick="hapusFormRusak(${counterRusak})" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>

                    <h4 class="font-bold text-gray-700 text-sm mb-2">Buku Rusak #${counterRusak}</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama & Kategori Otomatis --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Judul Buku</label>
                            <input type="text" value="${namaBuku}" class="w-full text-sm bg-gray-100 border-gray-300 rounded mb-2" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Kategori</label>
                            <input type="text" value="${kategoriBuku}" class="w-full text-sm bg-gray-100 border-gray-300 rounded mb-2" readonly>
                        </div>

                        {{-- Input Manual --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Nomor/Kode Buku</label>
                            <input type="text" name="rusak[${counterRusak}][nomor_buku]" class="w-full text-sm border-gray-300 rounded focus:ring-red-200 focus:border-red-400" placeholder="Contoh: B-001" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Jenis Kerusakan</label>
                            <select name="rusak[${counterRusak}][jenis_kerusakan]" class="w-full text-sm border-gray-300 rounded focus:ring-red-200">
                                <option value="Ringan">Ringan (Sobek/Coret)</option>
                                <option value="Berat">Berat (Halaman Hilang/Basah)</option>
                                <option value="Hilang">Hilang Total</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700">Catatan</label>
                            <textarea name="rusak[${counterRusak}][catatan]" class="w-full text-sm border-gray-300 rounded focus:ring-red-200" rows="1" placeholder="Detail kerusakan..."></textarea>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('container-rusak').insertAdjacentHTML('beforeend', html);
        }

        function hapusFormRusak(id) {
            const el = document.getElementById(`row-rusak-${id}`);
            if (el) {
                el.remove();
                counterRusak--;
                updateCounterUI();
            }
        }

        function updateCounterUI() {
            // Update tombol jika sudah max
            const btn = document.getElementById('btnTambahRusak');
            const info = document.getElementById('sisaBuku');

            info.innerText = (maxBuku - counterRusak);

            if (counterRusak >= maxBuku) {
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.disabled = true;
            } else {
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.disabled = false;
            }
        }
    </script>
</x-app-layout>
