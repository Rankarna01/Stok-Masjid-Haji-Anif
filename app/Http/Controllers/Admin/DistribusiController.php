<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DistribusiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $permintaans = Permintaan::where('status', 'Disetujui')
                ->with(['user', 'detail.barang.satuan', 'distribusi'])
                ->orderBy('tanggal', 'desc')
                ->get();
            return response()->json($permintaans);
        }

        return view('admin.distribusi.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permintaan_id' => 'required|exists:permintaan,id',
            'tanggal_distribusi' => 'required|date',
            'dokumentasi' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Data tidak valid atau foto terlalu besar (Max 2MB).', 'errors' => $validator->errors()], 422);
        }

        
        $existing = Distribusi::where('permintaan_id', $request->permintaan_id)->first();
        if ($existing) {
            return response()->json(['message' => 'Permintaan ini sudah didistribusikan.'], 400);
        }

        $path = null;
        if ($request->hasFile('dokumentasi')) {
            $path = $request->file('dokumentasi')->store('dokumentasi', 'public');
        }

        Distribusi::create([
            'permintaan_id' => $request->permintaan_id,
            'tanggal_distribusi' => $request->tanggal_distribusi,
            'dokumentasi' => $path
        ]);

        return response()->json(['message' => 'Berhasil mencatat distribusi dan mengunggah dokumentasi.']);
    }
}
