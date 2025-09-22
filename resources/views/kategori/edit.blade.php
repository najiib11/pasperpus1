<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ubah Kategori') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="nama" id="nama"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('nama', $kategori->nama) }}" required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('kategori.index') }}"
                       class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit"
                            class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
