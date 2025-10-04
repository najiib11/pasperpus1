<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Daftar Buku') }}
            </h2>
            <!-- Tombol Tambah Buku -->
                                @if(Auth::user()->hasRole('pustakawan'))

                {{-- jika pengguna admin dan pustakawan --}}
                <a href="{{ route('buku.create') }}"
                   class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 text-sm">
                    + Tambah Buku
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
        @foreach($kategoris as $kategori)
            <div>
                <!-- Header kategori -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-700">
                        {{ $kategori->nama }}
                    </h3>
                    <a href="{{ route('kategori.show', $kategori->id) }}"
                       class="text-blue-600 hover:underline flex items-center">
                        Lihat semua >
                    </a>
                </div>

                <!-- Card slider -->
                <div class="flex space-x-4 overflow-x-auto pb-4">
                    @forelse($kategori->buku as $buku)
                        <div class="w-48 flex-shrink-0 bg-white shadow rounded-lg overflow-hidden">
                            @if($buku->gambar)
                                <img src="{{ asset('storage/' . $buku->gambar) }}"
                                    alt="{{ $buku->judul }}"
                                    class="h-56 w-full object-cover">
                            @else
                                <div class="h-56 w-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 text-sm">No Image</span>
                                </div>
                            @endif
                            <div class="p-3 text-center space-y-2">
                                <h4 class="font-semibold text-sm truncate">{{ $buku->judul }}</h4>

                                <!-- Detail -->
                                <a href="{{ route('buku.show', $buku->id) }}"
                                   class="inline-block bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                                    Detail Buku
                                </a>
                                    @if ($roles[1]->name == 'pustakawan')
                                        <a href="{{ route('buku.edit', $buku->id) }}"
                                        class="inline-block bg-yellow-500 text-white text-xs px-3 py-1 rounded hover:bg-yellow-600">
                                            Edit
                                        </a>
                                    @endif



                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada buku di kategori ini.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
