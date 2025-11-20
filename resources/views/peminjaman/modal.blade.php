<div id="peminjamanModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-xl w-full">
        <h2 class="text-xl font-semibold mb-4 text-center">Detail Peminjaman</h2>

        <div id="peminjamanContent" class="mb-6"></div>

        <div class="flex justify-end gap-2">
            <button id="closePeminjamanModal" class="bg-gray-400 text-white px-4 py-2 rounded">
                Tutup
            </button>

            <form id="pengembalianForm" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Kembalikan Buku
                </button>
            </form>
        </div>
    </div>
</div>
