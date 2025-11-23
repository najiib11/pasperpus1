<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('peminjaman.index') }}"
            class="text-blue-100 hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Edit Data Peminjaman
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Peminjam (readonly) --}}
                    <div class="mb-4">
                        <label class="block font-medium">Nama Peminjam</label>
                        <input type="text" value="{{ $peminjaman->user->name }}" class="w-full border rounded p-2 bg-gray-100" readonly>
                        <input type="hidden" name="user_id" value="{{ $peminjaman->user_id }}">
                    </div>

                    {{-- Judul Buku (readonly, disabled + hidden agar tetap dikirim) --}}
                    <div class="mb-4">
                        <label class="block font-medium">Judul Buku</label>
                        <select class="w-full border rounded p-2 bg-gray-100" disabled>
                            @foreach($buku as $b)
                                <option value="{{ $b->id }}" {{ $peminjaman->buku_id == $b->id ? 'selected' : '' }}>
                                    {{ $b->judul }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="buku_id" value="{{ $peminjaman->buku_id }}">
                    </div>

                    {{-- Jumlah Buku (readonly) --}}
                    <div class="mb-4">
                        <label class="block font-medium">Jumlah Buku</label>
                        <input type="number" class="w-full border rounded p-2 bg-gray-100" value="{{ $peminjaman->jumlah }}" readonly>
                        <input type="hidden" name="jumlah" value="{{ $peminjaman->jumlah }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Tanggal Pinjam</label>
                        <input type="date"
                               class="w-full border rounded p-2 bg-gray-100"
                               value="{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('Y-m-d') }}"
                               readonly>
                        <input type="hidden" name="tanggal_pinjam" value="{{ $peminjaman->tanggal_pinjam }}">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block font-medium">Tenggat</label>
                        <input type="date"
                               class="w-full border rounded p-2 bg-gray-100"
                               value="{{ \Carbon\Carbon::parse($peminjaman->tenggat)->format('Y-m-d') }}"
                               readonly>
                        <input type="hidden" name="tenggat" value="{{ $peminjaman->tenggat }}">
                    </div>
                    

                    {{-- HANYA STATUS YANG BISA DI EDIT --}}
                    <div class="mb-4">
                        <label class="block font-medium">Status</label>
                        <select name="status" class="w-full border rounded p-2">
                            <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Di Kembalikan</option>
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
