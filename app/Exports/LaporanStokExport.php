<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class LaporanStokExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $row = $highestRow + 2;
                
                $sheet->setCellValue('B' . $row, 'Diketahui oleh :');
                $sheet->setCellValue('F' . $row, 'Medan, ' . Carbon::now()->translatedFormat('d F Y'));
                
                $row++;
                $sheet->setCellValue('B' . $row, 'Ketua Harian YHA');
                $sheet->setCellValue('F' . $row, 'Dibuat oleh :');
                
                $row++;
                $sheet->setCellValue('F' . $row, 'Staff Umum dan Koord. PKM YHA');

                $row += 5;
                $sheet->setCellValue('B' . $row, "H. M. Saf'i Sitepu, S.Ag, SH, MH");
                $sheet->getStyle('B' . $row)->getFont()->setBold(true)->setUnderline(true);
                
                $sheet->setCellValue('F' . $row, 'Muhammad Saputra, ST');
                $sheet->getStyle('F' . $row)->getFont()->setBold(true)->setUnderline(true);
            },
        ];
    }
}
