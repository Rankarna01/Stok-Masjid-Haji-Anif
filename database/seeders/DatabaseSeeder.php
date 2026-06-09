<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@yayasan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Koordinator Masjid A',
            'email' => 'koordinator@yayasan.com',
            'password' => Hash::make('password'),
            'role' => 'koordinator',
            'no_hp' => '081234567890',
            'nama_mesjid' => 'Masjid Al-Ikhlas',
            'alamat' => 'Jl. Kebersihan No 1'
        ]);

        // Kategori
        $kategoris = [
            ['nama_kategori' => 'Alat Pembersih Lantai', 'keterangan' => 'Sapu, pel, dll'],
            ['nama_kategori' => 'Cairan Pembersih', 'keterangan' => 'Pembersih lantai, kaca'],
            ['nama_kategori' => 'Pengharum Ruangan', 'keterangan' => 'Kapur barus, stella'],
            ['nama_kategori' => 'Peralatan Mandi/Toilet', 'keterangan' => 'Sikat WC, sabun cuci tangan'],
            ['nama_kategori' => 'Perlengkapan Ibadah', 'keterangan' => 'Pembersih karpet, sajadah'],
            ['nama_kategori' => 'Lainnya', 'keterangan' => 'Barang kebersihan lainnya'],
        ];
        
        foreach ($kategoris as $k) {
            Kategori::create($k);
        }

        // Satuan
        $satuans = [
            ['nama_satuan' => 'Pcs', 'keterangan' => 'Pieces / Buah'],
            ['nama_satuan' => 'Liter', 'keterangan' => 'Ukuran volume liter'],
            ['nama_satuan' => 'Botol', 'keterangan' => 'Kemasan botol'],
            ['nama_satuan' => 'Lusin', 'keterangan' => '12 Pcs'],
            ['nama_satuan' => 'Dus', 'keterangan' => 'Kemasan kardus / karton'],
            ['nama_satuan' => 'Pack', 'keterangan' => 'Kemasan pack'],
            ['nama_satuan' => 'Bungkus', 'keterangan' => 'Kemasan bungkus / sachet'],
            ['nama_satuan' => 'Jerigen', 'keterangan' => 'Kemasan jerigen besar'],
        ];
        
        foreach ($satuans as $s) {
            Satuan::create($s);
        }
    }
}
