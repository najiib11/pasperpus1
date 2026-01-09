<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peminjam',
            'Judul Buku',
            'Jumlah',
            'Tanggal Pinjam',
            'Tenggat',
            'Tanggal Kembali',
            'Status',
            'Denda (Rp)',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->user->name ?? 'User Terhapus',
            $row->buku->judul ?? 'Buku Terhapus',
            $row->jumlah,
            $row->tanggal_pinjam ? $row->tanggal_pinjam->format('d-m-Y') : '-',
            $row->tenggat ? $row->tenggat->format('d-m-Y') : '-',
            $row->tanggal_kembali ? $row->tanggal_kembali->format('d-m-Y') : '-',
            ucfirst($row->status),
            $row->denda ?? 0,
        ];
    }
}
