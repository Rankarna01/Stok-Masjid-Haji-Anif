<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Permintaan;
use App\Models\PermintaanDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermintaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permintaans = Permintaan::where('user_id', Auth::id())
                ->with(['detail.barang.satuan', 'distribusi'])
                ->latest()
                ->get();
            return response()->json($permintaans);
        }

        return view('koordinator.permintaan.index');
    }

    public function create()
    {
        $barangs = Barang::with(['kategori', 'satuan'])->where('stok', '>', 0)->get();
        return view('koordinator.permintaan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.alasan' => 'nullable|string',
            'items.*.bukti' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $permintaan = Permintaan::create([
                'user_id' => Auth::id(),
                'status' => 'Menunggu',
                'tanggal' => now()->toDateString(),
            ]);

            foreach ($request->items as $item) {
                // Opsional: Validasi apakah stok mencukupi
                $barang = Barang::findOrFail($item['barang_id']);
                if ($item['jumlah'] > $barang->stok) {
                    DB::rollback();
                    return response()->json([
                        'message' => "Stok {$barang->nama_barang} tidak mencukupi. (Sisa: {$barang->stok})"
                    ], 400);
                }

                $buktiPath = null;
                if (isset($item['bukti']) && $item['bukti']->isValid()) {
                    $buktiPath = $item['bukti']->store('bukti_permintaan', 'public');
                }

                PermintaanDetail::create([
                    'permintaan_id' => $permintaan->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'alasan' => $item['alasan'] ?? null,
                    'bukti_permintaan' => $buktiPath,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Permintaan barang berhasil diajukan',
                'redirect' => route('koordinator.permintaan.index')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $permintaan = Permintaan::where('user_id', Auth::id())
            ->with(['detail.barang.satuan'])
            ->findOrFail($id);
            
        return response()->json($permintaan);
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $permintaan = Permintaan::where('user_id', Auth::id())
                ->where('status', 'Menunggu')
                ->findOrFail($id);

            $permintaan->delete();

            DB::commit();

            return response()->json(['message' => 'Permintaan berhasil dibatalkan']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal membatalkan permintaan. Mungkin status sudah diproses.'], 500);
        }
    }
}
