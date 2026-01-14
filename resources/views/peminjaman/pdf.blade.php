<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #eee; }
        .text-right { text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Peminjaman Perpustakaan</h2>
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th width="5%" class="center">Jml</th> <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $p)
            <tr>
                <td class="center">{{ $loop->iteration }}</td>
                <td>{{ $p->user->name ?? '-' }}</td>
                <td>{{ $p->buku->judul ?? '-' }}</td>
                <td class="center">{{ $p->jumlah }}</td> <td>{{ $p->tanggal_pinjam ? $p->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                <td>{{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td class="text-right">Rp{{ number_format($p->denda, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total</strong></td>

                <td class="center"><strong>{{ $peminjamans->sum('jumlah') }}</strong></td>

                <td colspan="3"></td>

                <td class="text-right"><strong>Rp{{ number_format($peminjamans->sum('denda'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
