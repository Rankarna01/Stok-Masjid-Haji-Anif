<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\Distribusi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Kartu
        $totalBarang = Barang::count();
        $stokMenipis = Barang::where('stok', '<=', 5)->count();
        $permintaanBulanIni = Permintaan::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count();
        $permintaanPending = Permintaan::where('status', 'Menunggu')->count();
        
        // Data Chart - Permintaan vs Distribusi per Bulan (6 Bulan Terakhir)
        $chartData = [];
        $categories = [];
        $dataPermintaan = [];
        $dataDistribusi = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->startOfMonth()->subMonths($i);
            $categories[] = $month->translatedFormat('M Y');
            
            $countPermintaan = Permintaan::whereMonth('tanggal', $month->month)
                                        ->whereYear('tanggal', $month->year)
                                        ->count();
                                        
            $countDistribusi = Distribusi::whereMonth('tanggal_distribusi', $month->month)
                                        ->whereYear('tanggal_distribusi', $month->year)
                                        ->count();
                                        
            $dataPermintaan[] = $countPermintaan;
            $dataDistribusi[] = $countDistribusi;
        }

        // Permintaan Terbaru
        $permintaanTerbaru = Permintaan::with('user')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalBarang', 
            'stokMenipis', 
            'permintaanBulanIni', 
            'permintaanPending',
            'categories',
            'dataPermintaan',
            'dataDistribusi',
            'permintaanTerbaru'
        ));
    }
}
