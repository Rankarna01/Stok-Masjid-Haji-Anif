<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Alat Pembersih Lantai', 'keterangan' => 'Sapu, pel, kain lap, dll'],
            ['nama_kategori' => 'Cairan Pembersih', 'keterangan' => 'Pembersih lantai, kaca, karpet'],
            ['nama_kategori' => 'Pengharum Ruangan', 'keterangan' => 'Kapur barus, stella, pengharum otomatis'],
            ['nama_kategori' => 'Peralatan Mandi/Toilet', 'keterangan' => 'Sikat WC, sabun cuci tangan, pembersih porselen'],
            ['nama_kategori' => 'Perlengkapan Ibadah & Karpet', 'keterangan' => 'Pembersih karpet khusus, sajadah roll, vakum'],
            ['nama_kategori' => 'Lainnya', 'keterangan' => 'Barang kebersihan dan perawatan masjid lainnya'],
        ];
        
        foreach ($kategoris as $k) {
            Kategori::create($k);
        }
    }
}
