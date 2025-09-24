<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <!-- Tombol Kembali -->
            <a href="{{ route('buku.index') }}" class="text-white hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <!-- Judul -->
            <h2 class="font-semibold text-xl text-white leading-tight">
                Semua Buku Kategori : {{ $kategori->nama }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($kategori->buku as $buku)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    @if($buku->gambar)
                        <img src="{{ asset('storage/'.$buku->gambar) }}"
                             alt="{{ $buku->judul }}"
                             class="h-48 w-full object-cover">
                    @else
                        <div class="h-48 w-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif
                    <div class="p-3 text-center">
                        <h4 class="font-semibold text-sm truncate">{{ $buku->judul }}</h4>
                        <a href="{{ route('buku.show', $buku->id) }}"
                           class="mt-2 inline-block bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                            Detail Buku
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 col-span-full">Belum ada buku di kategori ini.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
