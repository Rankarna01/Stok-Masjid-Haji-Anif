<?php

namespace App\Exports;

use App\Models\Permintaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanPermintaanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate, $endDate, $status)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Permintaan::with(['user', 'detail.barang.satuan', 'distribusi']);
            
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }
        
        if ($this->status && $this->status !== 'Semua') {
            $query->where('status', $this->status);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Permintaan',
            'Tanggal',
            'Koordinator',
            'Wilayah / Mesjid',
            'Item Barang Diminta',
            'Status',
            'Status Distribusi'
        ];
    }

    public function map($permintaan): array
    {
        static $row = 0;
        $row++;
        
        $items = $permintaan->detail->map(function($d) {
            return ($d->barang->nama_barang ?? '-') . ' (' . $d->jumlah . ' ' . ($d->barang->satuan->nama_satuan ?? '') . ')';
        })->implode(', ');
        
        $statusDistribusi = $permintaan->distribusi ? 'Selesai disalurkan' : ($permintaan->status === 'Disetujui' ? 'Menunggu penyaluran' : '-');

        return [
            $row,
            'PRM-' . str_pad($permintaan->id, 4, '0', STR_PAD_LEFT),
            $permintaan->tanggal,
            $permintaan->user->name ?? '-',
            $permintaan->user->nama_mesjid ?? '-',
            $items,
            $permintaan->status,
            $statusDistribusi
        ];
    }
}
