<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('peminjaman.index') }}" class="text-white hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <!-- Judul header -->
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Tambah Peminjaman') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2">Nama Peminjam</label>
                        <select name="user_id" class="w-full border rounded p-2">
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">Buku</label>
                        <select name="buku_id" class="w-full border rounded p-2">
                            @foreach($buku as $b)
                                <option value="{{ $b->id }}">{{ $b->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
