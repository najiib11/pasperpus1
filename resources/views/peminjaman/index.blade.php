<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Daftar Peminjaman Buku') }}
            </h2>
            <a href="{{ route('peminjaman.create') }}"
               class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 text-sm">
                + Tambah Peminjaman
            </a>
        </div>
    </x-slot>

    <div class="py-12">
       <div class="p-6 text-gray-900">
            <div class="flex flex-wrap justify-center gap-6 mb-8">
                <!-- Card Peminjaman -->
                <div class="cursor-pointer flex-1 min-w-[250px] max-w-[400px] h-32 text-blue-500 bg-white hover:bg-blue-200 py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out flex flex-col justify-center items-center"
                    id="cardDipinjam" onclick="toggleTable('dipinjam')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a9 9 0 00-9 9v1a2 2 0 002 2h14a2 2 0 002-2v-1a9 9 0 00-9-9z" />
                    </svg>
                    <h3 class="font-semibold text-lg text-center">Catatan Peminjaman</h3>
                </div>

                <!-- Card Reservasi -->
            <div class="cursor-pointer flex-1 min-w-[250px] max-w-[400px] h-32 text-green-500 bg-white hover:bg-green-200 py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out flex flex-col justify-center items-center"
                    id="cardReservasi" onclick="toggleTable('reservasi')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5v14l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
                    </svg>
                    <h3 class="font-semibold text-lg text-center">Catatan Reservasi</h3>
                </div>

                <!-- Card Dikembalikan -->
                <div class="cursor-pointer flex-1 min-w-[250px] max-w-[400px] h-32 text-amber-500 bg-white hover:bg-amber-200 py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out flex flex-col justify-center items-center"
                    id="cardDikembalikan" onclick="toggleTable('dikembalikan')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V7a2 2 0 00-2-2h-4l-2-2-2 2H6a2 2 0 00-2 2v6m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6" />
                    </svg>
                    <h3 class="font-semibold text-lg text-center">Catatan Pengembalian</h3>
                </div>
            </div>
        </div>


        <div id="dipinjam" class="hidden overflow-x-auto bg-white shadow-md rounded-lg mb-6 w-full max-w-7xl mx-auto">
              <div class="flex justify-end p-4">
        <a href="{{ route('peminjaman.refresh') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg"
           onclick="return confirm('Hitung ulang denda berdasarkan tenggat per hari ini?')">
           Refresh Denda
        </a>
    </div>
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
                        @foreach($peminjamans->where('status', 'dipinjam') as $p)


                            <td class="border p-2 text-center">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $p->user->name ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $p->buku->judul ?? '-' }}</td>
                            <td class="border px-4 py-2 text-center">{{ $p->jumlah }}</td>

                            {{-- Format tanggal pinjam --}}
                            <td class="border px-4 py-2 text-center">
                                {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') : '-' }}
                            </td>

                            {{-- Format denda --}}
                            <td class="border px-4 py-2 text-center">
                                    <span class="text-red-600 font-bold">
                                        Rp{{ number_format($p->denda, 0, ',', '.') }}
                                    </span>

                            </td>



                            <td class="border flex justify-center px-3 py-2 gap-2">
                                <button onclick="showDetail({{ $p->id }})"
                                        class="bg-blue-500 text-white px-3 py-1 rounded">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tabel: Reservasi -->
            <div id="reservasi" class="hidden overflow-x-auto bg-white shadow-md rounded-lg mb-6 w-full max-w-7xl mx-auto">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead class="bg-green-400 text-white">
                        <tr>
                            <th class="p-2 border">No</th>
                            <th class="border px-4 py-2">Nama Peminjam</th>
                            <th class="border px-4 py-2">Judul Buku</th>
                            <th class="border px-4 py-2">Jumlah</th>
                            <th class="border px-4 py-2">Tanggal Pinjam</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse($reservasiGrouped as $bukuId => $reservasis)
                        <tr class="bg-green-100">
                            <td colspan="6" class="px-4 py-2 font-semibold border text-gray-800">
                                📖 {{ $reservasis->first()->buku->judul }}
                                <span class="ml-4 text-sm text-gray-600">Total Antrian: {{ $reservasis->count() }}</span>
                            </td>
                        </tr>

                        @foreach($reservasis as $index => $p)
                            <tr class="hover:bg-green-50">
                                <td class="border p-2 text-center">{{ $index + 1 }}</td>
                                <td class="border px-4 py-2">{{ $p->user->name }}</td>
                                <td class="border px-4 py-2">{{ $p->buku->judul }}</td>
                                <td class="border px-4 py-2 text-center">{{ $p->jumlah }}</td>
                                <td class="border px-4 py-2">{{ $p->tanggal_pinjam }}</td>
                                <td class="border flex justify-center px-3 py-2 gap-2">
                                    <button onclick="showDetail({{ $p->id }})"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada reservasi.</td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Tabel: Sudah Dikembalikan -->
            <div id="dikembalikan" class="hidden overflow-x-auto bg-white shadow-md rounded-lg mb-6 w-full max-w-7xl mx-auto">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead class="bg-amber-500 text-white">
                        <tr>
                            <th class="p-2 border">No</th>
                            <th class="border px-4 py-2">Nama Peminjam</th>
                            <th class="border px-4 py-2">Judul Buku</th>
                            <th class="border px-4 py-2">Jumlah</th>
                            <th class="border px-4 py-2">Denda</th>

                            <th class="border px-4 py-2">Tanggal Pinjam</th>
                            <th class="border px-4 py-2">Tanggal Dikembalikan</th>

                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjamans->where('status', 'dikembalikan') as $p)
                        <tr>
                            <td class="border p-2 text-center">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $p->user->name }}</td>
                            <td class="border px-4 py-2">{{ $p->buku->judul }}</td>
                            <td class="border px-4 py-2 text-center">{{ $p->jumlah }}</td>
                                   {{-- Format denda --}}
                            <td class="border px-4 py-2 text-center">

                                    <span class="text-red-600 font-bold">
                                        Rp{{ number_format($p->denda, 0, ',', '.') }}
                                    </span>

                            </td>
                            <td class="border px-4 py-2">{{ $p->tanggal_pinjam }}</td>
                            <td class="border px-4 py-2">{{ $p->tanggal_kembali }}</td>

                            <td class="border flex justify-center px-3 py-2 gap-2">
                                <button onclick="showDetail({{ $p->id }})"
                                        class="bg-amber-500 text-white px-3 py-1 rounded">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal Detail Peminjaman -->
            <div id="detailModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-xl w-full">
                    <h2 class="text-xl font-semibold mb-6 text-center">Detail Peminjaman</h2>

                    <!-- Isi detail peminjaman akan diisi melalui JavaScript -->
                    <div id="modalContent" class="mb-6">
                        <!-- Detail content goes here -->
                    </div>

                    <div class="flex justify-between items-center gap-4 mt-4">
                        <!-- Button Close -->
                        <button id="btnCloseModal" class="bg-gray-400 text-white px-6 py-2 rounded-md hover:bg-gray-500 transition-all duration-200">
                            Tutup
                        </button>

                        <!-- Action Button (Kembalikan/Edit) -->
                        <button id="btnAction" class="hidden px-6 py-2 rounded-md text-white disabled:bg-gray-400" disabled>
                            <!-- Button text will be updated dynamically -->
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle visibility of tables based on the status clicked
        function toggleTable(status) {
            const tables = ['dipinjam', 'reservasi', 'dikembalikan'];
            tables.forEach(tableId => {
                const table = document.getElementById(tableId);
                table.classList.add('hidden');
            });

            const tableToShow = document.getElementById(status);
            tableToShow.classList.remove('hidden');
        }

        // Function to show detail modal
       function showDetail(id) {
    const peminjaman = @json($peminjamans);

    const selectedPeminjaman = peminjaman.find(p => p.id === id);

    const modalContent = document.getElementById('modalContent');
    const btnAction = document.getElementById('btnAction');
    const btnCloseModal = document.getElementById('btnCloseModal');

    // Hitung tenggat waktu = tanggal_pinjam + 7 hari
    const tanggalPinjam = new Date(selectedPeminjaman.tanggal_pinjam);
    const tenggat = new Date(tanggalPinjam);
    tenggat.setDate(tenggat.getDate() + 7);
    const tenggatFormatted = tenggat.toISOString().split('T')[0];

    // Isi konten modal
    modalContent.innerHTML = `
        <p><strong>Nama Peminjam:</strong> ${selectedPeminjaman.user.name}</p>
        <p><strong>Judul Buku:</strong> ${selectedPeminjaman.buku.judul}</p>
        <p><strong>Tanggal Pinjam:</strong> ${selectedPeminjaman.tanggal_pinjam}</p>
        <p><strong>Tenggat Waktu:</strong> ${tenggatFormatted}</p>
        <p><strong>Tanggal Kembali:</strong> ${selectedPeminjaman.tanggal_kembali ?? '-'}</p>
        <p><strong>Status:</strong> ${selectedPeminjaman.status.charAt(0).toUpperCase() + selectedPeminjaman.status.slice(1)}</p>
        <p><strong>Denda:</strong> Rp ${selectedPeminjaman.denda.toLocaleString()}</p>
    `;

    // Reset tombol terlebih dahulu
    btnAction.classList.remove('hidden', 'bg-blue-500', 'bg-yellow-500');
    btnAction.disabled = true;
    btnAction.textContent = '';
    btnAction.onclick = null; // Hapus event lama

    // Tampilkan tombol sesuai status
    if (selectedPeminjaman.status === 'dipinjam') {
        btnAction.textContent = 'Kembalikan';
        btnAction.classList.add('bg-blue-500');
        btnAction.disabled = false;
        btnAction.onclick = function () {
            window.location.href = `/peminjaman/kembalikan/${id}`;
        };
        } else if (selectedPeminjaman.status === 'reservasi') {
            btnAction.textContent = 'Konfirmasi';
            btnAction.classList.add('bg-green-500', 'hover:bg-green-600');
            btnAction.disabled = false;
            btnAction.onclick = function () {
                Swal.fire({
                    title: 'Konfirmasi Reservasi?',
                    text: 'Apakah kamu yakin ingin mengubah status reservasi ini menjadi peminjaman?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a', // warna hijau Tailwind
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Konfirmasi',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/peminjaman/konfirmasi/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Reservasi berhasil dikonfirmasi menjadi peminjaman.',
                                    icon: 'success',
                                    confirmButtonColor: '#16a34a'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Gagal mengonfirmasi peminjaman.',
                                    icon: 'error',
                                    confirmButtonColor: '#d33'
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat mengonfirmasi.',
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
                        });
                    }
                });
            };
        }
 else {
        btnAction.classList.add('hidden');
        btnAction.disabled = true;
    }

    // Tampilkan modal
    document.getElementById('detailModal').classList.remove('hidden');

    // Tutup modal
    btnCloseModal.onclick = function () {
        document.getElementById('detailModal').classList.add('hidden');
    };
}




        // Optional: Implement search functionality across all tables
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let keyword = this.value.toLowerCase();

            // Search for each table by status
            let tables = ['dipinjam', 'reservasi', 'dikembalikan'];

            tables.forEach(tableId => {
                let rows = document.querySelectorAll(`#${tableId} tbody tr`);
                rows.forEach(row => {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(keyword) ? "" : "none";
                });
            });
        });
    </script>
</x-app-layout>
