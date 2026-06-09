@extends('layouts.app', ['title' => 'Dashboard Koordinator'])

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-textDark mb-1">Selamat datang, {{ Auth::user()->name }}! 👋</h2>
    <p class="text-sm text-gray-500">Berikut adalah ringkasan aktivitas permintaan barang untuk wilayah {{ Auth::user()->nama_mesjid }}.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Cards -->
    <div class="bg-card rounded-2xl p-6 card-shadow border border-border flex items-center gap-4 hover:-translate-y-1 transition-smooth cursor-pointer">
        <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Total Pengajuan</p>
            <h3 class="text-2xl font-bold text-textDark">{{ $totalPermintaan }}</h3>
        </div>
    </div>

    <div class="bg-card rounded-2xl p-6 card-shadow border border-border flex items-center gap-4 hover:-translate-y-1 transition-smooth cursor-pointer">
        <div class="w-14 h-14 rounded-2xl bg-success/10 text-success flex items-center justify-center shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Disetujui</p>
            <h3 class="text-2xl font-bold text-textDark">{{ $permintaanDisetujui }}</h3>
        </div>
    </div>

    <div class="bg-card rounded-2xl p-6 card-shadow border border-border flex items-center gap-4 hover:-translate-y-1 transition-smooth cursor-pointer">
        <div class="w-14 h-14 rounded-2xl bg-warning/10 text-warning flex items-center justify-center shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Menunggu</p>
            <h3 class="text-2xl font-bold text-textDark">{{ $permintaanMenunggu }}</h3>
        </div>
    </div>

    <div class="bg-card rounded-2xl p-6 card-shadow border border-border flex items-center gap-4 hover:-translate-y-1 transition-smooth cursor-pointer">
        <div class="w-14 h-14 rounded-2xl bg-danger/10 text-danger flex items-center justify-center shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Ditolak</p>
            <h3 class="text-2xl font-bold text-textDark">{{ $permintaanDitolak }}</h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Chart Area -->
    <div class="lg:col-span-2 bg-card rounded-2xl p-6 card-shadow border border-border">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-textDark">Tren Pengajuan Barang</h3>
            <span class="px-3 py-1 bg-gray-100 text-xs font-semibold text-gray-500 rounded-lg">6 Bulan Terakhir</span>
        </div>
        <div id="mainChart" class="w-full h-80"></div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-card rounded-2xl p-6 card-shadow border border-border flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-textDark">Pengajuan Terakhir</h3>
            <a href="{{ route('koordinator.permintaan.index') }}" class="text-sm font-semibold text-primary hover:underline">Lihat Riwayat</a>
        </div>
        
        <div class="flex-1 space-y-4">
            @forelse($permintaanTerbaru as $prm)
            <div class="flex gap-4 items-start p-3 hover:bg-gray-50 rounded-xl transition-smooth border border-transparent hover:border-gray-100">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0 font-bold text-xs">
                    PRM
                </div>
                <div class="flex-1 overflow-hidden">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="text-sm font-bold text-textDark">PRM-{{ str_pad($prm->id, 4, '0', STR_PAD_LEFT) }}</h4>
                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-lg shrink-0 
                            @if($prm->status == 'Menunggu') bg-warning/10 text-warning 
                            @elseif($prm->status == 'Disetujui') bg-success/10 text-success 
                            @else bg-danger/10 text-danger @endif">
                            {{ $prm->status }}
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 mb-1 truncate">
                        {{ $prm->detail->count() }} jenis barang diajukan
                    </p>
                    <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($prm->tanggal)->translatedFormat('d M Y') }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-400">
                Belum ada data pengajuan.
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: [{
                name: 'Pengajuan',
                data: @json($dataPermintaan)
            }],
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'Poppins, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            colors: ['#0F766E'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: @json($categories),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#94a3b8' } }
            },
            yaxis: {
                labels: { style: { colors: '#94a3b8' } }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            },
            tooltip: {
                theme: 'light',
                y: { formatter: function (val) { return val + " Pengajuan" } }
            }
        };

        var chart = new ApexCharts(document.querySelector("#mainChart"), options);
        chart.render();
    });
</script>
@endpush
@endsection
