<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuans = [
            ['nama_satuan' => 'Pcs', 'keterangan' => 'Pieces / Buah'],
            ['nama_satuan' => 'Liter', 'keterangan' => 'Ukuran volume liter'],
            ['nama_satuan' => 'Botol', 'keterangan' => 'Kemasan botol'],
            ['nama_satuan' => 'Lusin', 'keterangan' => '12 Pcs'],
            ['nama_satuan' => 'Dus', 'keterangan' => 'Kemasan kardus / karton'],
            ['nama_satuan' => 'Pack', 'keterangan' => 'Kemasan pack'],
            ['nama_satuan' => 'Bungkus', 'keterangan' => 'Kemasan bungkus / sachet'],
            ['nama_satuan' => 'Jerigen', 'keterangan' => 'Kemasan jerigen besar'],
            ['nama_satuan' => 'Roll', 'keterangan' => 'Kemasan gulungan / roll'],
        ];
        
        foreach ($satuans as $s) {
            Satuan::create($s);
        }
    }
}
