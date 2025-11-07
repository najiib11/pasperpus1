
<x-app-layout>
      <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Kelola Denda') }}
            </h2>
        </div>
    </x-slot>
    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                            Daftar Denda Peminjaman Buku
                        </h1>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr>
                                    <th class="p-2 border text-center">No</th>
                                    <th class="border px-4 py-2">Nama Anggota</th>
                                    <th class="border px-4 py-2">Jumlah Pinjaman (buku)</th>
                                    <th class="border px-4 py-2">Tanggal Kembali</th>
                                    <th class="border px-4 py-2">Denda (Rp)</th>
                                    <th class="border px-4 py-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamans as $index => $peminjaman)
                                    <tr class="hover:bg-blue-50 transition">
                                        <td class="border p-2 text-center">{{ $index + 1 }}</td>
                                        <td class="border px-4 py-2">{{ $peminjaman->user->name ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-center">{{ $peminjaman->jumlah ?? 0 }}</td>
                                        <td class="border px-4 py-2 text-center">
                                            {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td class="border px-4 py-2 text-center font-bold text-red-600">
                                            Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            @if($peminjaman->denda > 0)
                                                <form action="{{ route('peminjaman.bayarDenda', $peminjaman->id) }}"
                                                    method="POST" onsubmit="return confirmBayar(event)">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg shadow">
                                                        Bayar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-gray-500">Belum ada data denda</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmBayar(event) {
            event.preventDefault();
            if (confirm('Apakah Anda yakin ingin membayar denda ini?')) {
                event.target.submit();
            }
        }
    </script>

</x-app-layout>
