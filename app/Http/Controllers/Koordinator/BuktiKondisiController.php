<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuktiKondisiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $distribusi = Distribusi::whereHas('permintaan', function ($query) {
                $query->where('user_id', Auth::id());
            })->with(['permintaan.detail.barang.satuan'])->latest()->get();
            
            return response()->json($distribusi);
        }

        return view('koordinator.bukti.index');
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'bukti_terima' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $distribusi = Distribusi::whereHas('permintaan', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        if ($request->hasFile('bukti_terima')) {
            $path = $request->file('bukti_terima')->store('bukti_terima', 'public');
            $distribusi->bukti_terima = $path;
            $distribusi->tanggal_terima = now();
            $distribusi->save();
        }

        return response()->json([
            'message' => 'Bukti penerimaan barang berhasil diunggah.',
            'data' => $distribusi
        ]);
    }
}
