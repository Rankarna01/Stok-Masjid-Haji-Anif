<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Distribusi;
use App\Models\Permintaan;
use App\Models\StokMasuk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanStokExport;
use App\Exports\LaporanPermintaanExport;
use App\Exports\LaporanDistribusiExport;

class LaporanController extends Controller
{
    // Stok
    public function stok(Request $request)
    {
        if ($request->ajax()) {
            $query = StokMasuk::with(['barang.kategori', 'barang.satuan']);
            
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }
            
            if ($request->filled('kategori_id') && $request->kategori_id !== 'Semua') {
                $query->whereHas('barang', function($q) use ($request) {
                    $q->where('kategori_id', $request->kategori_id);
                });
            }
            
            if ($request->filled('search')) {
                $query->whereHas('barang', function($q) use ($request) {
                    $q->where('nama_barang', 'like', '%' . $request->search . '%')
                      ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
                });
            }
            
            return response()->json($query->orderBy('tanggal', 'desc')->paginate(10));
        }
        
        $kategoris = \App\Models\Kategori::all();
        return view('admin.laporan.stok', compact('kategoris'));
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

            return response()->json($query->latest()->paginate(10));
        }

        return view('admin.laporan.permintaan');
    }

    // Distribusi
    public function distribusi(Request $request)
    {
        if ($request->ajax()) {
            $query = Distribusi::with(['permintaan.user', 'permintaan.detail.barang.satuan']);
            
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_distribusi', [$request->start_date, $request->end_date]);
            }

            return response()->json($query->latest()->paginate(10));
        }

        return view('admin.laporan.distribusi');
    }

    // Export PDF Stok
    public function exportStokPdf(Request $request)
    {
        $query = StokMasuk::with(['barang.kategori', 'barang.satuan']);
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        
        if ($request->filled('kategori_id') && $request->kategori_id !== 'Semua') {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }
        
        if ($request->filled('search')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }
        
        $stokMasuks = $query->orderBy('tanggal', 'desc')->get();
        $filter = $request->only(['start_date', 'end_date', 'kategori_id', 'search']);
        $pdf = Pdf::loadView('admin.laporan.pdf.stok', compact('stokMasuks', 'filter'));
        return $pdf->stream('Laporan_Historis_Stok_Masuk.pdf');
    }

    // Export Excel Stok
    public function exportStokExcel(Request $request)
    {
        return Excel::download(new LaporanStokExport(
            $request->kategori_id, 
            $request->search,
            $request->start_date,
            $request->end_date
        ), 'Laporan_Historis_Stok_Masuk.xlsx');
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

    // Export PDF Distribusi
    public function exportDistribusiPdf(Request $request)
    {
        $query = Distribusi::with(['permintaan.user', 'permintaan.detail.barang.satuan']);
            
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_distribusi', [$request->start_date, $request->end_date]);
        }

        $distribusis = $query->latest()->get();
        $filter = $request->only(['start_date', 'end_date']);
        
        $pdf = Pdf::loadView('admin.laporan.pdf.distribusi', compact('distribusis', 'filter'))->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan_Distribusi_Barang.pdf');
    }

    // Export Excel Distribusi
    public function exportDistribusiExcel(Request $request)
    {
        return Excel::download(new LaporanDistribusiExport($request->start_date, $request->end_date), 'Laporan_Distribusi_Barang.xlsx');
    }
}
