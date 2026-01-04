<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Catatan Pengembalian') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('reservasi.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                    Lihat Reservasi
                </a>
                <a href="{{ route('peminjaman.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded text-sm">
                    Lihat Peminjaman
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto bg-white shadow-md rounded-lg w-full max-w-7xl mx-auto">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead class="bg-blue-400 text-white">
                        <tr>
                            <th class="p-2 border">No</th>
                            <th class="border px-4 py-2">Nama Peminjam</th>
                            <th class="border px-4 py-2">Judul Buku</th>
                            <th class="border px-4 py-2">Jumlah</th>
                            <th class="border px-4 py-2">Tanggal Pinjam</th>
                            <th class="border px-4 py-2">Tanggal Pengembalian</th>
                            <th class="border px-4 py-2">Denda</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ( $peminjamans as $p )
                        <tr>
                            <td class="border text-center">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $p->user->name ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $p->buku->judul ?? '-' }}</td>
                            <td class="border px-4 py-2 text-center">{{ $p->jumlah }}</td>
                            <td class="border px-4 py-2 text-center">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                            <td class="border px-4 py-2 text-center">{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d-m-Y') }}</td>
                            <td class="border px-4 py-2 text-center text-red-600 font-bold">
                                Rp{{ number_format($p->denda, 0, ',', '.') }}
                            </td>
                            <td class="border flex justify-center gap-2 py-2">
                                <button onclick="showDetail({{ $p->id }})" class="bg-blue-500 text-white px-3 py-1 rounded">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7"class="text-center py-4 text-gray-500">
                                    Tidak Ada Data Pengembalian
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('pengembalian.modal')
   <script>
    // Kirim semua data peminjaman ke JS saat render
    const peminjamans = @json($peminjamans);

    function formatTanggal(tanggal) {
        const date = new Date(tanggal);
        const d = String(date.getDate()).padStart(2, '0');
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const y = date.getFullYear();
        return `${d}-${m}-${y}`;
    }

    // Dibuat global supaya bisa dipanggil dari tombol *Detail*
    function showDetail(id) {
        // Cari peminjaman berdasarkan id
        const data = peminjamans.find(p => p.id === id);

        if (!data) {
            alert('Data tidak ditemukan');
            return;
        }

        const modal   = document.getElementById('pengembalianModal');
        const content = document.getElementById('modalContent');

        // Cek dulu, biar tidak null
        if (!modal || !content) {
            console.error('Elemen dengan id "pengembalianModal" atau "modalContent" tidak ditemukan di HTML.');
            return;
        }

        content.innerHTML = `
            <div class="space-y-2">
                <p><strong>Nama Peminjam:</strong> ${data.user?.name ?? '-'}</p>
                <p><strong>Judul Buku:</strong> ${data.buku?.judul ?? '-'}</p>
                <p><strong>Jumlah:</strong> ${data.jumlah}</p>
                <p><strong>Tanggal Pinjam:</strong> ${data.tanggal_pinjam ? formatTanggal(data.tanggal_pinjam) : '-'}</p>
                <p><strong>Status:</strong> ${data.status}</p>
                <p><strong>Denda:</strong> Rp${Number(data.denda ?? 0).toLocaleString('id-ID')}</p>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button id="btnCloseModal" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    Tutup
                </button>
            </div>
        `;

        // Tampilkan modal
        modal.classList.remove('hidden');

        // Pasang *event listener* setelah tombol ada di DOM
        const btnClose = document.getElementById('btnCloseModal');
        if (btnClose) {
            btnClose.addEventListener('click', () => {
                modal.classList.add('hidden');
            }, { once: true }); // once: true biar tidak numpuk listener
        }
    }

    // HAPUS / KOMENTARI BAGIAN INI KARENA BIKIN ERROR
    // document.getElementById('btnCloseModal').addEventListener('click', function () {
    //     document.getElementById('detailModal').classList.add('hidden');
    // });
</script>

</x-app-layout>
