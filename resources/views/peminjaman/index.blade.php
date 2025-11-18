<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Catatan Peminjaman') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('reservasi.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                    Lihat Reservasi
                </a>
                <a href="{{ route('pengembalian.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded text-sm">
                    Lihat Pengembalian
                </a>
                <a href="{{ route('peminjaman.create') }}" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 text-sm">
                    + Tambah Peminjaman
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="p-6 text-gray-900">
            <div class="flex justify-end p-4">
                <a href="{{ route('peminjaman.refresh') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg"
                   onclick="return confirm('Hitung ulang denda berdasarkan tenggat per hari ini?')">
                   Refresh Denda
                </a>
            </div>

            <div class="overflow-x-auto bg-white shadow-md rounded-lg w-full max-w-7xl mx-auto">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead class="bg-blue-400 text-white">
                        <tr>
                            <th class="p-2 border">No</th>
                            <th class="border px-4 py-2">Nama Peminjam</th>
                            <th class="border px-4 py-2">Judul Buku</th>
                            <th class="border px-4 py-2">Jumlah</th>
                            <th class="border px-4 py-2">Tanggal Pinjam</th>
                            <th class="border px-4 py-2">Denda</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamans as $p)
                        <tr>
                            <td class="border text-center">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $p->user->name ?? 'Tidak ada data pengembalian' }}</td>
                            <td class="border px-4 py-2">{{ $p->buku->judul ?? 'Tidak ada data pengembalian' }}</td>
                            <td class="border px-4 py-2 text-center">{{ $p->jumlah ?? '-' }}</td>
                            <td class="border px-4 py-2 text-center">
                                {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="border px-4 py-2 text-center text-red-600 font-bold">
                                Rp{{ $p->denda != null ? number_format($p->denda, 0, ',', '.') : '0' }}
                            </td>
                            <td class="border flex justify-center gap-2 py-2">
                                <button onclick="showDetail({{ $p->id }})" class="bg-blue-500 text-white px-3 py-1 rounded btn-detail" data-id="{{ $p->id }}" >
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">
                                Tidak Ada Data Pengembalian
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    @include('peminjaman.modal')
    <script>
        // Kirim semua data peminjaman ke JS saat render
        const peminjamans = @json($peminjamans);

        function showDetail(id) {
            const data = peminjamans.find(p => p.id === id);
            if (!data) return alert('Data tidak ditemukan');

            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');

            // Update konten modal
            content.innerHTML = `
                <div class="space-y-2">
                    <p><strong>Nama Peminjam:</strong> ${data.user?.name ?? '-'}</p>
                    <p><strong>Judul Buku:</strong> ${data.buku?.judul ?? '-'}</p>
                    <p><strong>Jumlah:</strong> ${data.jumlah}</p>
                    <p><strong>Tanggal Pinjam:</strong> ${data.tanggal_pinjam ?? '-'}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>Denda:</strong> Rp${Number(data.denda).toLocaleString('id-ID')}</p>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button id="btnCloseModal" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                        Tutup
                    </button>

                    <form id="returnForm" method="POST" action="/peminjaman/kembalikan/${data.id}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Konfirmasi Pengembalian
                        </button>
                    </form>
                </div>
            `;

            modal.classList.remove('hidden');

            // Close modal
            document.getElementById('btnCloseModal').addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            // SweetAlert konfirmasi sebelum submit
            const form = document.getElementById('returnForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // stop default submit
                Swal.fire({
                    title: 'Konfirmasi Pengembalian?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // submit POST
                    }
                });
            });
        }

        document.getElementById('btnCloseModal').addEventListener('click', function () {
            document.getElementById('detailModal').classList.add('hidden');
        });
    </script>
</x-app-layout>

