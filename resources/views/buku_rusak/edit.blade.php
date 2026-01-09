<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Edit Laporan Kerusakan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('buku-rusak.update', $rusak->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                        <input type="text" value="{{ $rusak->buku->judul ?? '-' }}" class="w-full bg-gray-100 border-gray-300 rounded" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nomor/Kode Buku</label>
                        <input type="text" name="nomor_buku" value="{{ $rusak->nomor_buku }}" class="w-full border-gray-300 rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Jenis Kerusakan</label>
                        <select name="jenis_kerusakan" class="w-full border-gray-300 rounded">
                            <option value="Ringan" {{ $rusak->jenis_kerusakan == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                            <option value="Berat" {{ $rusak->jenis_kerusakan == 'Berat' ? 'selected' : '' }}>Berat</option>
                            <option value="Hilang" {{ $rusak->jenis_kerusakan == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Catatan Detail</label>
                        <textarea name="catatan" rows="3" class="w-full border-gray-300 rounded">{{ $rusak->catatan }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('buku-rusak.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
