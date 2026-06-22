<?php

namespace App\Exports;

use App\Models\Distribusi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class LaporanDistribusiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Distribusi::with(['permintaan.user', 'permintaan.detail.barang.satuan']);
            
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_distribusi', [$this->startDate, $this->endDate]);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Permintaan',
            'Tanggal Penyaluran',
            'Koordinator',
            'Wilayah / Mesjid',
            'Item Barang Disalurkan',
            'Status Penerimaan',
            'Tanggal Diterima'
        ];
    }

    public function map($distribusi): array
    {
        static $row = 0;
        $row++;
        
        $items = $distribusi->permintaan->detail->map(function($d) {
            return ($d->barang->nama_barang ?? '-') . ' (' . $d->jumlah . ' ' . ($d->barang->satuan->nama_satuan ?? '') . ')';
        })->implode(', ');
        
        $statusPenerimaan = $distribusi->bukti_terima ? 'Diterima Koor' : 'Telah Disalurkan';
        $tanggalDiterima = $distribusi->tanggal_terima ?? '-';

        return [
            $row,
            'PRM-' . str_pad($distribusi->permintaan_id, 4, '0', STR_PAD_LEFT),
            $distribusi->tanggal_distribusi,
            $distribusi->permintaan->user->name ?? '-',
            $distribusi->permintaan->user->nama_mesjid ?? '-',
            $items,
            $statusPenerimaan,
            $tanggalDiterima
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
