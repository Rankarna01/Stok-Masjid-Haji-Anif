<?php

namespace App\Exports;

use App\Models\Barang;
use App\Models\StokMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class LaporanStokExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $kategoriId;
    protected $search;
    protected $startDate;
    protected $endDate;

    public function __construct($kategoriId = null, $search = null, $startDate = null, $endDate = null)
    {
        $this->kategoriId = $kategoriId;
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = StokMasuk::with(['barang.kategori', 'barang.satuan']);
        
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }
        
        if ($this->kategoriId && $this->kategoriId !== 'Semua') {
            $query->whereHas('barang', function($q) {
                $q->where('kategori_id', $this->kategoriId);
            });
        }
        
        if ($this->search) {
            $query->whereHas('barang', function($q) {
                $q->where('nama_barang', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
            });
        }
        
        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Masuk',
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Jumlah Masuk',
            'Sisa Stok',
            'Satuan'
        ];
    }

    public function map($item): array
    {
        static $row = 0;
        $row++;
        
        return [
            $row,
            Carbon::parse($item->tanggal)->format('d/m/Y'),
            $item->barang->kode_barang ?? '-',
            $item->barang->nama_barang ?? '-',
            $item->barang->kategori->nama_kategori ?? '-',
            '+' . $item->jumlah,
            $item->barang->stok ?? 0,
            $item->barang->satuan->nama_satuan ?? '-'
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
