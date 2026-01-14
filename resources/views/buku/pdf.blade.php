<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Buku</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 5px; text-align: left; }
        th { background-color: #e2e8f0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">Laporan Data Buku Perpustakaan</h2>
        <p style="margin:5px 0 0;">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th class="text-center">Tahun</th>
                <th>Kategori</th>
                <th>Sumber</th>
                <th class="text-center">Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bukus as $b)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $b->judul }}</td>
                <td>{{ $b->penulis }}</td>
                <td>{{ $b->penerbit }}</td>
                <td class="text-center">{{ $b->tahun_terbit }}</td>
                <td>{{ $b->kategori->nama ?? '-' }}</td>
                <td>{{ ucfirst($b->sumber_pengadaan) }}</td>
                <td class="text-center">{{ $b->stok }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><strong>TOTAL STOK</strong></td>
                <td class="text-center"><strong>{{ $bukus->sum('stok') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
