<!DOCTYPE html>
<html>
<head>
    <title>Laporan Buku Rusak</title>
    <style>
        /* Styling khusus untuk PDF (CSS Standar) */
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* Header Laporan */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
        }

        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        /* Kolom Spesifik */
        .col-no { width: 5%; text-align: center; }
        .col-tgl { width: 12%; }
        .col-kode { width: 12%; }
        .col-status { width: 10%; text-align: center; }

        /* Footer Tanda Tangan */
        .footer {
            margin-top: 40px;
            text-align: right;
            margin-right: 30px;
            page-break-inside: avoid; /* Agar tanda tangan tidak terpotong ke halaman baru */
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            display: inline-block;
            width: 200px;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT / HEADER --}}
    <div class="header">
        <h2>Laporan Buku Rusak & Hilang</h2>
        <h3>SMKS PASUNDAN 1 CIANJUR</h3>
        <p>Jl. Pasundan No. 1, Cianjur, Jawa Barat</p>
        <p style="font-size: 10px; font-style: italic; margin-top: 5px;">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}
        </p>
    </div>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-tgl">Tanggal</th>
                <th>Judul Buku</th>
                <th class="col-kode">Kode Buku</th>
                <th>Peminjam</th>
                <th class="col-status">Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataRusak as $d)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $d->created_at->format('d/m/Y') }}</td>
                <td>{{ $d->buku->judul ?? 'Buku Terhapus' }}</td>
                <td>{{ $d->nomor_buku }}</td>
                <td>{{ $d->peminjaman->user->name ?? 'User Terhapus' }}</td>
                <td style="text-align: center;">{{ $d->jenis_kerusakan }}</td>
                <td>{{ $d->catatan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">
                    Tidak ada data kerusakan pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER / TANDA TANGAN --}}
    <div class="footer">
        <p>Cianjur, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,</p>
        <p>Kepala Perpustakaan</p>

        <div class="signature-line"></div>
        <p>(__________________________)</p>
    </div>

</body>
</html>
