<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Daftar Reservasi Buku
        </h2>
    </x-slot>

    <div class="p-6">
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Nama Anggota</th>
                    <th class="border px-4 py-2">Nomor Antrian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservasi as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-4 py-2">{{ $item->user->name }}</td>
                        <td class="border px-4 py-2 text-center">{{ $item->antrian }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
