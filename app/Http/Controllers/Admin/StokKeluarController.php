<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokKeluar;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StokKeluarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $stokKeluars = StokKeluar::with('barang.satuan')->latest()->get();
            return response()->json($stokKeluars);
        }
        
        $barangs = Barang::all();
        return view('admin.stok-keluar.index', compact('barangs'));
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

            $barang = Barang::findOrFail($request->barang_id);
            
            // Cek ketersediaan stok
            if ($barang->stok < $request->jumlah) {
                return response()->json(['message' => 'Gagal! Stok barang tidak mencukupi. (Sisa: ' . $barang->stok . ')'], 400);
            }

            $stokKeluar = StokKeluar::create($request->all());

            // Kurangi stok barang
            $barang->stok -= $request->jumlah;
            $barang->save();

            DB::commit();

            return response()->json([
                'message' => 'Stok keluar berhasil dicatat',
                'data' => $stokKeluar->load('barang')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);

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
            $oldBarang = Barang::findOrFail($stokKeluar->barang_id);
            $oldBarang->stok += $stokKeluar->jumlah; // Jika keluar dihapus, stok bertambah
            
            // Jika barang diganti
            if ($oldBarang->id_barang != $request->barang_id) {
                $oldBarang->save();
                $newBarang = Barang::findOrFail($request->barang_id);
            } else {
                $newBarang = $oldBarang;
            }
            
            // Cek stok untuk barang tujuan
            if ($newBarang->stok < $request->jumlah) {
                DB::rollback();
                return response()->json(['message' => 'Gagal! Stok barang tidak mencukupi untuk update.'], 400);
            }

            // Kurangi dengan stok baru
            $newBarang->stok -= $request->jumlah;
            $newBarang->save();

            // Update record
            $stokKeluar->update($request->all());

            DB::commit();

            return response()->json([
                'message' => 'Data stok keluar berhasil diperbarui',
                'data' => $stokKeluar->load('barang')
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

            $stokKeluar = StokKeluar::findOrFail($id);
            
            // Kembalikan stok barang (tambahkan kembali)
            $barang = Barang::findOrFail($stokKeluar->barang_id);
            $barang->stok += $stokKeluar->jumlah;
            
            $barang->save();
            $stokKeluar->delete();

            DB::commit();

            return response()->json(['message' => 'Stok keluar berhasil dihapus. Stok barang telah dikembalikan.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
