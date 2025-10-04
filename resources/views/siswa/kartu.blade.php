<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Figtree', sans-serif;
        }
        .card {
            width: 500px;
            margin: 2% auto; /* ini yang bikin center horizontal */
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background: #2563eb; /* biru */
            color: white;
            text-align: center;
            padding: 0px 8px;
        }
        .content {
            display: flex;
            flex-direction: row;
            /* justify-content: space-between;  */
            width: 450px;
            padding: 0 20px;
            font-size: 12px;
        }
        .footer {
            background: #f3f4f6;
            text-align: center;
            font-size: 10px;
            padding: 6px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h3>KARTU ANGGOTA PERPUSTAKAAN</h3>
            <p>SMKS PASUNDAN 1 CIANJUR</p>
        </div>
        <div class="content">
            <div class="content-left">
                <p><strong>Nama:</strong> {{ $siswa->nama }}</p>
                <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
                <p><strong>Jurusan:</strong> {{ $siswa->jurusan }}</p>
                <p><strong>Kelas:</strong> {{ $siswa->kelas }}</p>
                <p><strong>Email:</strong> {{ $siswa->email }}</p>
                <p><strong>Telepon:</strong> {{ $siswa->telepon }}</p>
                <p><strong>TTL:</strong> {{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir }}</p>
                <p><strong>Alamat:</strong> {{ $siswa->alamat }}</p>
            </div>
        </div>

        <div class="footer">
            Kartu ini sah tanpa tanda tangan
        </div>
    </div>
</body>
</html>
