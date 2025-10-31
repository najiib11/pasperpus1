<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Catatan Reservasi') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('pengembalian.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded text-sm">
                    Lihat Pengembalian
                </a>
                <a href="{{ route('peminjaman.index') }}" class="bg-blue-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
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
                            <th class="border px-4 py-2">Denda</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservasiGrouped as $group)
                            @foreach ($group as $data)
                            <tr>
                                <td class="border text-center">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $data->user->name ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $data->buku->judul ?? '-' }}</td>
                                <td class="border px-4 py-2 text-center">{{ $data->jumlah }}</td>
                                <td class="border px-4 py-2 text-center">{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d-m-Y') }}</td>
                                <td class="border px-4 py-2 text-center text-red-600 font-bold">
                                    Rp{{ number_format($data->denda, 0, ',', '.') }}
                                </td>
                                <td class="border flex justify-center gap-2 py-2">
                                    <button onclick="showDetail({{ $data->id }})" class="bg-blue-500 text-white px-3 py-1 rounded">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">
                                Tidak Ada Data Reservasi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('peminjaman.modal') {{-- pastikan modal ada id="detailModal" dan btnCloseModal --}}

    @push('scripts')
    <script>
        // Flatten array untuk akses mudah
        const peminjamans = Object.values(@json($reservasiGrouped)).flat();

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
                            Konfirmasi Peminjaman
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
                    title: 'Konfirmasi Peminjaman?',
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


        function confirmReturn(id) {
            Swal.fire({
                title: 'Konfirmasi Pengembalian?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/peminjaman/kembalikan/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => res.json())
                    .then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message ?? 'Berhasil mengembalikan buku',
                        }).then(() => location.reload());
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mengembalikan buku',
                        });
                        console.error(err);
                    });
                }
            });
        }

        document.getElementById('btnCloseModal').addEventListener('click', function () {
            document.getElementById('detailModal').classList.add('hidden');
        });
    </script>
    @endpush
</x-app-layout>
