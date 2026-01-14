<!DOCTYPE html>
<html>
<head>
    <title>Laporan Reservasi</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background-color: #e2e8f0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row { background-color: #f0fdf4; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">Laporan Reservasi Buku</h2>
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
                <th width="5%" class="text-center">Jml</th>
                <th class="text-center">Tgl Reservasi</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasis as $r)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $r->user->name ?? '-' }}</td>
                <td>{{ $r->buku->judul ?? '-' }}</td>
                <td class="text-center">{{ $r->jumlah }}</td>
                <td class="text-center">{{ $r->tanggal_pinjam ? $r->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                <td class="text-center">Reservasi</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-center">{{ $reservasis->sum('jumlah') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
