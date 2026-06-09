<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanStokExport;
use App\Exports\LaporanPermintaanExport;

class LaporanController extends Controller
{
    // Stok
    public function stok(Request $request)
    {
        if ($request->ajax()) {
            $barangs = Barang::with(['kategori', 'satuan'])->get();
            return response()->json($barangs);
        }
        return view('admin.laporan.stok');
    }

    // Permintaan
    public function permintaan(Request $request)
    {
        if ($request->ajax()) {
            $query = Permintaan::with(['user', 'detail.barang.satuan', 'distribusi']);
            
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }
            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status', $request->status);
            }

            return response()->json($query->latest()->get());
        }

        return view('admin.laporan.permintaan');
    }

    // Export PDF Stok
    public function exportStokPdf()
    {
        $barangs = Barang::with(['kategori', 'satuan'])->get();
        $pdf = Pdf::loadView('admin.laporan.pdf.stok', compact('barangs'));
        return $pdf->stream('Laporan_Stok_Barang.pdf');
    }

    // Export Excel Stok
    public function exportStokExcel()
    {
        return Excel::download(new LaporanStokExport, 'Laporan_Stok_Barang.xlsx');
    }

    // Export PDF Permintaan
    public function exportPermintaanPdf(Request $request)
    {
        $query = Permintaan::with(['user', 'detail.barang.satuan', 'distribusi']);
            
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        $permintaans = $query->latest()->get();
        $filter = $request->only(['start_date', 'end_date', 'status']);
        
        $pdf = Pdf::loadView('admin.laporan.pdf.permintaan', compact('permintaans', 'filter'))->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan_Permintaan_Barang.pdf');
    }

    // Export Excel Permintaan
    public function exportPermintaanExcel(Request $request)
    {
        return Excel::download(new LaporanPermintaanExport($request->start_date, $request->end_date, $request->status), 'Laporan_Permintaan_Barang.xlsx');
    }
}
