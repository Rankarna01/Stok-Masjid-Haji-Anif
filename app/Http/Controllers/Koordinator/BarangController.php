<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $barangs = Barang::with(['kategori', 'satuan'])->latest()->get();
            return response()->json($barangs);
        }

        return view('koordinator.barang.index');
    }
}
