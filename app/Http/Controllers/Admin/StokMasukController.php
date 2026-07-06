<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $stokMasuks = StokMasuk::with('barang.satuan')->latest()->get();
            return response()->json($stokMasuks);
        }
        
        $barangs = Barang::all();
        return view('admin.stok-masuk.index', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barang,id_barang',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $stokMasuk = StokMasuk::create($request->all());

            // Tambah stok barang
            $barang = Barang::findOrFail($request->barang_id);
            $barang->stok += $request->jumlah;
            $barang->save();

            DB::commit();

            return response()->json([
                'message' => 'Stok masuk berhasil dicatat',
                'data' => $stokMasuk->load('barang')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barang,id_barang',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Kembalikan stok lama
            $oldBarang = Barang::findOrFail($stokMasuk->barang_id);
            $oldBarang->stok -= $stokMasuk->jumlah;
            $oldBarang->save();

            // Update stok masuk
            $stokMasuk->update($request->all());

            // Tambahkan stok baru
            $newBarang = Barang::findOrFail($request->barang_id);
            $newBarang->stok += $request->jumlah;
            $newBarang->save();

            DB::commit();

            return response()->json([
                'message' => 'Data stok masuk berhasil diperbarui',
                'data' => $stokMasuk->load('barang')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $stokMasuk = StokMasuk::findOrFail($id);
            
            // Kembalikan stok barang (kurangi)
            $barang = Barang::findOrFail($stokMasuk->barang_id);
            $barang->stok -= $stokMasuk->jumlah;
            
            // Jangan biarkan stok minus
            if ($barang->stok < 0) {
                DB::rollback();
                return response()->json(['message' => 'Gagal menghapus! Stok barang akan menjadi minus.'], 400);
            }
            
            $barang->save();
            $stokMasuk->delete();

            DB::commit();

            return response()->json(['message' => 'Stok masuk berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
