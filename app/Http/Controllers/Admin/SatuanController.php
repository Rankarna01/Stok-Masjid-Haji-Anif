<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $satuans = Satuan::latest()->get();
            return response()->json($satuans);
        }
        
        return view('admin.satuan.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_satuan' => 'required|string|max:50|unique:satuan',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $satuan = Satuan::create($request->all());

        return response()->json([
            'message' => 'Satuan berhasil ditambahkan',
            'data' => $satuan
        ]);
    }

    public function update(Request $request, string $id)
    {
        $satuan = Satuan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_satuan' => 'required|string|max:50|unique:satuan,nama_satuan,' . $id,
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $satuan->update($request->all());

        return response()->json([
            'message' => 'Satuan berhasil diperbarui',
            'data' => $satuan
        ]);
    }

    public function destroy(string $id)
    {
        $satuan = Satuan::findOrFail($id);
        
        // Prevent deletion if items exist
        if ($satuan->barang()->exists()) {
            return response()->json(['message' => 'Gagal! Satuan ini masih digunakan pada barang yang terdaftar.'], 400);
        }
        
        $satuan->delete();

        return response()->json(['message' => 'Satuan berhasil dihapus']);
    }
}
