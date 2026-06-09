<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Illuminate\Http\Request;

class RiwayatStokController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $masuk = StokMasuk::with('barang.satuan')->get()->map(function ($item) {
                $item->jenis = 'masuk';
                return $item;
            });

            $keluar = StokKeluar::with('barang.satuan')->get()->map(function ($item) {
                $item->jenis = 'keluar';
                return $item;
            });

            // Combine and sort by date descending
            $riwayat = $masuk->concat($keluar)->sortByDesc('tanggal')->values();

            return response()->json($riwayat);
        }

        return view('admin.riwayat-stok.index');
    }
}
