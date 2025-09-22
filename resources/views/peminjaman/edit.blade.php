<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Peminjaman
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium">Nama Peminjam</label>
                        <select name="user_id" class="w-full border rounded p-2">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $peminjaman->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Judul Buku</label>
                        <select name="buku_id" class="w-full border rounded p-2">
                            @foreach($buku as $b)
                                <option value="{{ $b->id }}" {{ $peminjaman->buku_id == $b->id ? 'selected' : '' }}>
                                    {{ $b->judul }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" value="{{ $peminjaman->tanggal_pinjam }}" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Tenggat</label>
                        <input type="date" name="tenggat" value="{{ $peminjaman->tenggat }}" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Status</label>
                        <select name="status" class="w-full border rounded p-2">
                            <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="reservasi" {{ $peminjaman->status == 'reservasi' ? 'selected' : '' }}>Reservasi</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('peminjaman.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
