<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Catatan Reservasi') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('pengembalian.index') }}"
                    class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded text-sm">
                    Lihat Pengembalian
                </a>
                <a href="{{ route('peminjaman.index') }}"
                    class="bg-blue-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
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
                            <th class="border px-4 py-2">Tanggal Reservasi</th>
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
                                    <td class="border px-4 py-2 text-center">
                                        {{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d-m-Y') }}
                                    </td>
                                    <td class="border px-4 py-2 text-center text-red-600 font-bold">
                                        Rp{{ number_format($data->denda, 0, ',', '.') }}
                                    </td>
                                    <td class="border flex justify-center gap-2 py-2">
                                        <button onclick="showDetailReservasi({{ $data->id }})"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">
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

    @include('reservasi.modal') {{-- pastikan modal ada id="detailModal" dan btnCloseModal --}}

    @push('scripts')
        <script>
            // Ambil semua reservasi dalam bentuk array
            const reservasis = Object.values(@json($reservasiGrouped)).flat();

            function showDetailReservasi(id) {
                const data = reservasis.find(r => r.id === id);
                if (!data) return alert("Data tidak ditemukan!");

                const modal = document.getElementById("reservasiModal");
                const content = document.getElementById("reservasiContent");
                const form = document.getElementById("reservasiKofirmasiForm");

                function formatTanggal(tanggal) {
                    const date = new Date(tanggal);
                    const d = String(date.getDate()).padStart(2, '0');
                    const m = String(date.getMonth() + 1).padStart(2, '0');
                    const y = date.getFullYear(); // kalau mau 2 digit → y.toString().slice(2)
                    return `${d}-${m}-${y}`;
                }


                // Set form action POST untuk konfirmasi
                form.action = `/reservasi-admin/konfirmasi/${data.id}`;

                // Isi konten modal
                content.innerHTML = `
                <p><strong>Nama Peminjam:</strong> ${data.user?.name ?? '-'}</p>
                <p><strong>Judul Buku:</strong> ${data.buku?.judul ?? '-'}</p>
                <p><strong>Jumlah:</strong> ${data.jumlah}</p>
                  <p><strong>Tanggal Pinjam:</strong> ${data.tanggal_pinjam ? formatTanggal(data.tanggal_pinjam) : '-'}</p>
                <p><strong>Status:</strong> ${data.status}</p>
                <p><strong>Denda:</strong> Rp${Number(data.denda).toLocaleString('id-ID')}</p>
            `;

                modal.classList.remove("hidden"); // Tampilkan modal

                // Tombol Tutup Modal
                document.getElementById("closeReservasiModal").onclick = () => {
                    modal.classList.add("hidden");
                };
            }
        </script>
    @endpush


</x-app-layout>
