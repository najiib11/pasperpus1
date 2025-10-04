<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('guru.index') }}" class="text-white hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl text-white font-semibold">Detail Guru</h1>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        @if ($guru->foto)
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $guru->foto) }}"
                                     alt="Foto Guru"
                                     class="w-40 h-40 object-cover rounded-lg shadow-md border">
                            </div>
                        @endif

                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                {{ $guru->nama }}
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600 text-sm">NIP</p>
                                    <p class="font-medium text-gray-900">{{ $guru->nip }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-600 text-sm">Email</p>
                                    <p class="font-medium text-gray-900">{{ $guru->email }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-600 text-sm">Telepon</p>
                                    <p class="font-medium text-gray-900">{{ $guru->telepon ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-600 text-sm">Jenis Kelamin</p>
                                    <p class="font-medium text-gray-900">{{ $guru->jenis_kelamin }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-600 text-sm">Tempat Lahir</p>
                                    <p class="font-medium text-gray-900">{{ $guru->tempat_lahir }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-600 text-sm">Tanggal Lahir</p>
                                    <p class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->format('d-m-Y') }}
                                    </p>
                                </div>

                                <div class="md:col-span-2">
                                    <p class="text-gray-600 text-sm">Alamat</p>
                                    <p class="font-medium text-gray-900">{{ $guru->alamat }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex gap-3">
                                <a href="{{ route('guru.edit', $guru->id) }}"
                                   class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                    Edit
                                </a>

                                <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="delete-btn px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>

                                <a href="{{ route('guru.index') }}"
                                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // SweetAlert konfirmasi hapus
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Yakin hapus data ini?',
                    text: "Data guru akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>
