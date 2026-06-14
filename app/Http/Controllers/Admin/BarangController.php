<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $barangs = Barang::with(['kategori', 'satuan'])->latest()->get();
            return response()->json($barangs);
        }
        
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        
        return view('admin.barang.index', compact('kategoris', 'satuans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate Kode Barang (e.g. BRG-0001)
        $latestBarang = Barang::orderBy('id', 'desc')->first();
        $nextId = $latestBarang ? $latestBarang->id + 1 : 1;
        $kodeBarang = 'BRG-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $data = $request->all();
        $data['kode_barang'] = $kodeBarang;

        $barang = Barang::create($data);

        return response()->json([
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang->load(['kategori', 'satuan'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $barang->update($request->all());

        return response()->json([
            'message' => 'Data Barang berhasil diperbarui',
            'data' => $barang->load(['kategori', 'satuan'])
        ]);
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json(['message' => 'Barang berhasil dihapus']);
    }
}
