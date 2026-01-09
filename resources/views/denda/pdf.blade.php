<!DOCTYPE html>
<html>
<head>
    <title>Laporan Denda</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background-color: #fee2e2; } /* Merah muda */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row { background-color: #fef2f2; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">Laporan Denda Keterlambatan</h2>
        <p style="margin:5px 0 0;">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
        @if(request('tanggal_awal'))
            <p style="margin:2px 0;">Periode: {{ \Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th width="10%" class="text-center">Total Buku</th>
                <th class="text-center">Tgl Kembali</th>
                <th class="text-right">Total Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dendas as $d)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $d->user->name ?? '-' }}</td>
                <td>{{ $d->buku->judul ?? '-' }}</td>
                <td class="text-center">{{ $d->jumlah }}</td>
                <td class="text-center">{{ $d->tanggal_kembali ? $d->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                <td class="text-right">Rp{{ number_format($d->denda, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">GRAND TOTAL</td>
                <td class="text-center">{{ $dendas->sum('jumlah') }}</td>
                <td></td>
                <td class="text-right">Rp{{ number_format($dendas->sum('denda'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
