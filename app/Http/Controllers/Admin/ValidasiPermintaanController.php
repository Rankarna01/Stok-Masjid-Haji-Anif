<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permintaan;
use App\Models\Barang;
use App\Models\StokKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ValidasiPermintaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permintaans = Permintaan::with(['user', 'detail.barang.satuan'])
                ->latest()
                ->get();
            return response()->json($permintaans);
        }

        return view('admin.validasi-permintaan.index');
    }

    public function proses(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Status tidak valid'], 400);
        }

        try {
            DB::beginTransaction();

            $permintaan = Permintaan::with('detail.barang')->findOrFail($id);

            if ($permintaan->status !== 'Menunggu') {
                return response()->json(['message' => 'Permintaan ini sudah diproses sebelumnya.'], 400);
            }

            $permintaan->status = $request->status;
            $permintaan->save();

            if ($request->status === 'Disetujui') {
                foreach ($permintaan->detail as $detail) {
                    $barang = $detail->barang;
                    
                    if ($barang->stok < $detail->jumlah) {
                        DB::rollback();
                        return response()->json([
                            'message' => "Stok {$barang->nama_barang} tidak mencukupi untuk memenuhi permintaan ini. (Sisa: {$barang->stok})"
                        ], 400);
                    }

                    // Potong stok
                    $barang->stok -= $detail->jumlah;
                    $barang->save();

                    // Catat ke riwayat stok keluar
                    StokKeluar::create([
                        'barang_id' => $barang->id_barang,
                        'jumlah' => $detail->jumlah,
                        'tanggal' => now()->toDateString(),
                        'keterangan' => "Penyaluran Permintaan #PRM-" . str_pad($permintaan->id_permintaan, 4, '0', STR_PAD_LEFT) . " untuk Koordinator " . $permintaan->user->nama_mesjid
                    ]);
                }
            }

            DB::commit();

            $pesan = $request->status === 'Disetujui' 
                ? 'Permintaan disetujui dan stok barang telah dipotong otomatis.' 
                : 'Permintaan telah ditolak.';

            return response()->json(['message' => $pesan]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
