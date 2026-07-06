<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'nama_sistem' => 'Sistem Inventaris Stok Masjid',
            'nama_yayasan' => 'Yayasan Haji Anif',
            'alamat' => 'Jl. Haji Anif No. 1, Medan, Sumatera Utara',
            'telepon' => '061-1234567',
            'email' => 'info@yayasanhajianif.org',
        ]);
    }
}
