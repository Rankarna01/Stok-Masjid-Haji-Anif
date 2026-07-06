<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Administrator
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@yayasan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Koordinator Umum (Masjid Al-Ikhlas / Medan)
        User::create([
            'name' => 'Koordinator Umum',
            'email' => 'koordinator@yayasan.com',
            'password' => Hash::make('password'),
            'role' => 'koordinator',
            'no_hp' => '081234567800',
            'nama_mesjid' => 'Masjid Haji Anif - Medan',
            'alamat' => 'Jl. Haji Anif No. 1, Medan, Sumatera Utara'
        ]);

        // 3. 14 Koordinator Wilayah Sesuai Request
        $koordinators = [
            [
                'name' => 'Mhd Saufi Ibrahim',
                'wilayah' => 'Tiga Juhar',
                'email' => 'saufi.tigajuhar@yayasan.com',
                'no_hp' => '081234567801',
            ],
            [
                'name' => 'M. Natsir Siregar',
                'wilayah' => 'Siantar',
                'email' => 'natsir.siantar@yayasan.com',
                'no_hp' => '081234567802',
            ],
            [
                'name' => 'Sulaiman',
                'wilayah' => 'Batu Bara',
                'email' => 'sulaiman.batubara@yayasan.com',
                'no_hp' => '081234567803',
            ],
            [
                'name' => 'Agusul Khair',
                'wilayah' => 'Tebing-tebing',
                'email' => 'agusul.tebing@yayasan.com',
                'no_hp' => '081234567804',
            ],
            [
                'name' => 'Dedi Darma',
                'wilayah' => 'Stabat',
                'email' => 'dedi.stabat@yayasan.com',
                'no_hp' => '081234567805',
            ],
            [
                'name' => 'Erman sakti Hutabarat',
                'wilayah' => 'Tapteng & Sibolga',
                'email' => 'erman.sibolga@yayasan.com',
                'no_hp' => '081234567806',
            ],
            [
                'name' => 'Irhamuddin',
                'wilayah' => 'Binjai',
                'email' => 'irhamuddin.binjai@yayasan.com',
                'no_hp' => '081234567807',
            ],
            [
                'name' => 'Tuah Aman',
                'wilayah' => 'Karo',
                'email' => 'tuahaman.karo@yayasan.com',
                'no_hp' => '081234567808',
            ],
            [
                'name' => 'Salman Abdullah T',
                'wilayah' => 'Asahan',
                'email' => 'salman.asahan@yayasan.com',
                'no_hp' => '081234567809',
            ],
            [
                'name' => 'Irwansyah S',
                'wilayah' => 'Brandan',
                'email' => 'irwansyah.brandan@yayasan.com',
                'no_hp' => '081234567810',
            ],
            [
                'name' => 'Herman Harahap',
                'wilayah' => 'Padang sidempuan',
                'email' => 'herman.sidempuan@yayasan.com',
                'no_hp' => '081234567811',
            ],
            [
                'name' => 'Sulaiman Hasibuan',
                'wilayah' => 'Lubuk Pakam',
                'email' => 'sulaiman.pakam@yayasan.com',
                'no_hp' => '081234567812',
            ],
            [
                'name' => 'M. Hanafi',
                'wilayah' => 'Rampah',
                'email' => 'hanafi.rampah@yayasan.com',
                'no_hp' => '081234567813',
            ],
            [
                'name' => 'Ahmad Darwis Rambe',
                'wilayah' => 'Perbaungan',
                'email' => 'darwis.perbaungan@yayasan.com',
                'no_hp' => '081234567814',
            ],
        ];

        foreach ($koordinators as $koor) {
            User::create([
                'name' => $koor['name'],
                'email' => $koor['email'],
                'password' => Hash::make('password'),
                'role' => 'koordinator',
                'no_hp' => $koor['no_hp'],
                'nama_mesjid' => 'Masjid Haji Anif - ' . $koor['wilayah'],
                'alamat' => 'Wilayah Koordinator ' . $koor['wilayah'] . ', Sumatera Utara',
            ]);
        }
    }
}
