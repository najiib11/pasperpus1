<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('buku.show', $buku->id) }}" class="text-white hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-xl text-white leading-tight">
                Edit Stok Buku
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('buku.update', $buku->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">Judul Buku</label>
                    <input type="text" value="{{ $buku->judul }}" disabled
                           class="form-control w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}"
                           class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                    @error('stok') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4">
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Update Stok
                    </button>
                    <a href="{{ route('buku.show', $buku->id) }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
