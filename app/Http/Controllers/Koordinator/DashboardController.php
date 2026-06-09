<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistik
        $totalPermintaan = Permintaan::where('user_id', $userId)->count();
        $permintaanDisetujui = Permintaan::where('user_id', $userId)->where('status', 'Disetujui')->count();
        $permintaanMenunggu = Permintaan::where('user_id', $userId)->where('status', 'Menunggu')->count();
        $permintaanDitolak = Permintaan::where('user_id', $userId)->where('status', 'Ditolak')->count();

        // Data Chart - Permintaan 6 Bulan Terakhir
        $chartData = [];
        $categories = [];
        $dataPermintaan = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->startOfMonth()->subMonths($i);
            $categories[] = $month->translatedFormat('M Y');
            
            $count = Permintaan::where('user_id', $userId)
                                ->whereMonth('tanggal', $month->month)
                                ->whereYear('tanggal', $month->year)
                                ->count();
                                        
            $dataPermintaan[] = $count;
        }

        // 5 Permintaan Terbaru
        $permintaanTerbaru = Permintaan::where('user_id', $userId)
                                        ->with('detail.barang')
                                        ->latest()
                                        ->take(5)
                                        ->get();

        return view('koordinator.dashboard.index', compact(
            'totalPermintaan',
            'permintaanDisetujui',
            'permintaanMenunggu',
            'permintaanDitolak',
            'categories',
            'dataPermintaan',
            'permintaanTerbaru'
        ));
    }
}
