<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID Kategori
        $katLantai = Kategori::where('nama_kategori', 'Alat Pembersih Lantai')->first()?->id_kategori ?? 1;
        $katCairan = Kategori::where('nama_kategori', 'Cairan Pembersih')->first()?->id_kategori ?? 2;
        $katHarum  = Kategori::where('nama_kategori', 'Pengharum Ruangan')->first()?->id_kategori ?? 3;
        $katToilet = Kategori::where('nama_kategori', 'Peralatan Mandi/Toilet')->first()?->id_kategori ?? 4;
        $katIbadah = Kategori::where('nama_kategori', 'Perlengkapan Ibadah & Karpet')->first()?->id_kategori ?? 5;
        $katLain   = Kategori::where('nama_kategori', 'Lainnya')->first()?->id_kategori ?? 6;

        // Ambil ID Satuan
        $satPcs     = Satuan::where('nama_satuan', 'Pcs')->first()?->id_satuan ?? 1;
        $satLiter   = Satuan::where('nama_satuan', 'Liter')->first()?->id_satuan ?? 2;
        $satBotol   = Satuan::where('nama_satuan', 'Botol')->first()?->id_satuan ?? 3;
        $satLusin   = Satuan::where('nama_satuan', 'Lusin')->first()?->id_satuan ?? 4;
        $satDus     = Satuan::where('nama_satuan', 'Dus')->first()?->id_satuan ?? 5;
        $satPack    = Satuan::where('nama_satuan', 'Pack')->first()?->id_satuan ?? 6;
        $satBungkus = Satuan::where('nama_satuan', 'Bungkus')->first()?->id_satuan ?? 7;
        $satJerigen = Satuan::where('nama_satuan', 'Jerigen')->first()?->id_satuan ?? 8;
        $satRoll    = Satuan::where('nama_satuan', 'Roll')->first()?->id_satuan ?? 9;

        $barangs = [
            [
                'kode_barang' => 'BRG-0001',
                'nama_barang' => 'Sapu Lidi Halaman Masjid',
                'kategori_id' => $katLantai,
                'satuan_id'   => $satPcs,
                'stok'        => 35,
                'keterangan'  => 'Sapu lidi tebal gagang bambu untuk halaman dan pelataran masjid',
            ],
            [
                'kode_barang' => 'BRG-0002',
                'nama_barang' => 'Sapu Lantai Nylon Nago',
                'kategori_id' => $katLantai,
                'satuan_id'   => $satPcs,
                'stok'        => 40,
                'keterangan'  => 'Sapu lantai dalam ruang sholat serat halus tidak merusak keramik',
            ],
            [
                'kode_barang' => 'BRG-0003',
                'nama_barang' => 'Kain Pel Lantai Microfiber',
                'kategori_id' => $katLantai,
                'satuan_id'   => $satPcs,
                'stok'        => 50,
                'keterangan'  => 'Kain pel daya serap tinggi mudah diperas',
            ],
            [
                'kode_barang' => 'BRG-0004',
                'nama_barang' => 'Wipol Pembersih Lantai Cemara 780ml',
                'kategori_id' => $katCairan,
                'satuan_id'   => $satBungkus,
                'stok'        => 80,
                'keterangan'  => 'Cairan pembersih dan disinfektan lantai aroma harum cemara',
            ],
            [
                'kode_barang' => 'BRG-0005',
                'nama_barang' => 'Pembersih Kaca Cling Refill 425ml',
                'kategori_id' => $katCairan,
                'satuan_id'   => $satBungkus,
                'stok'        => 60,
                'keterangan'  => 'Cairan pembersih kaca jendela dan pintu masjid',
            ],
            [
                'kode_barang' => 'BRG-0006',
                'nama_barang' => 'Stella Pengharum Ruangan Jeruk 400ml',
                'kategori_id' => $katHarum,
                'satuan_id'   => $satBotol,
                'stok'        => 45,
                'keterangan'  => 'Pengharum ruangan semprot untuk ruang sholat dan ruang koordinator',
            ],
            [
                'kode_barang' => 'BRG-0007',
                'nama_barang' => 'Kapur Barus Bagus Toilet 500gr',
                'kategori_id' => $katHarum,
                'satuan_id'   => $satPack,
                'stok'        => 70,
                'keterangan'  => 'Kapur barus penghilang bau untuk toilet dan tempat wudhu',
            ],
            [
                'kode_barang' => 'BRG-0008',
                'nama_barang' => 'Sikat WC Gagang Panjang Kaku',
                'kategori_id' => $katToilet,
                'satuan_id'   => $satPcs,
                'stok'        => 30,
                'keterangan'  => 'Sikat khusus pembersih kloset dan lantai kamar mandi',
            ],
            [
                'kode_barang' => 'BRG-0009',
                'nama_barang' => 'Sabun Cuci Tangan Lifebuoy Refill 400ml',
                'kategori_id' => $katToilet,
                'satuan_id'   => $satBungkus,
                'stok'        => 90,
                'keterangan'  => 'Sabun cuci tangan anti bakteri untuk keran wudhu dan wastafel',
            ],
            [
                'kode_barang' => 'BRG-0010',
                'nama_barang' => 'Harpic Pembersih Porselen Toilet 450ml',
                'kategori_id' => $katToilet,
                'satuan_id'   => $satBotol,
                'stok'        => 55,
                'keterangan'  => 'Cairan pembersih kerak keramik dan kloset kamar mandi masjid',
            ],
            [
                'kode_barang' => 'BRG-0011',
                'nama_barang' => 'Pembersih Karpet Khusus Rugbee 4L',
                'kategori_id' => $katIbadah,
                'satuan_id'   => $satJerigen,
                'stok'        => 15,
                'keterangan'  => 'Cairan pencuci dan pengharum karpet sajadah masjid',
            ],
            [
                'kode_barang' => 'BRG-0012',
                'nama_barang' => 'Sajadah Roll Tebal 10 Meter',
                'kategori_id' => $katIbadah,
                'satuan_id'   => $satRoll,
                'stok'        => 8,
                'keterangan'  => 'Sajadah gulung cadangan untuk sholat Jumat dan hari raya',
            ],
            [
                'kode_barang' => 'BRG-0013',
                'nama_barang' => 'Kanebo Serat Tinggi Synthetic Cloth',
                'kategori_id' => $katLain,
                'satuan_id'   => $satPcs,
                'stok'        => 65,
                'keterangan'  => 'Lap kanebo serbaguna untuk mengeringkan area wudhu dan mimbar',
            ],
            [
                'kode_barang' => 'BRG-0014',
                'nama_barang' => 'Kain Lap Microfiber Multipurpose',
                'kategori_id' => $katLain,
                'satuan_id'   => $satLusin,
                'stok'        => 25,
                'keterangan'  => 'Lap microfiber halus untuk membersihkan debu mimbar dan rak Al-Quran',
            ],
            [
                'kode_barang' => 'BRG-0015',
                'nama_barang' => 'Plastik Sampah Hitam Besar 80x100cm',
                'kategori_id' => $katLain,
                'satuan_id'   => $satPack,
                'stok'        => 100,
                'keterangan'  => 'Kantong sampah ukuran besar untuk tempat sampah area masjid',
            ],
        ];

        foreach ($barangs as $brg) {
            Barang::create($brg);
        }
    }
}
