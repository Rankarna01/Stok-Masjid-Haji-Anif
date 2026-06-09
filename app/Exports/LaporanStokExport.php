<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanStokExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Barang::with(['kategori', 'satuan'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Stok Tersedia',
            'Satuan',
            'Keterangan'
        ];
    }

    public function map($barang): array
    {
        static $row = 0;
        $row++;
        
        return [
            $row,
            $barang->kode_barang,
            $barang->nama_barang,
            $barang->kategori->nama_kategori ?? '-',
            $barang->stok,
            $barang->satuan->nama_satuan ?? '-',
            $barang->keterangan ?? '-'
        ];
    }
}
