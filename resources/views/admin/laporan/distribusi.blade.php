@extends('layouts.app', ['title' => 'Laporan Distribusi Barang'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="laporanDistribusi()">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-textDark">Laporan Distribusi Barang</h2>
            <p class="text-sm text-gray-500">Riwayat penyaluran barang ke koordinator.</p>
        </div>
        <div class="flex gap-2">
            <button @click="exportPdf" class="bg-danger/10 text-danger px-4 py-2 rounded-xl text-sm font-semibold hover:bg-danger hover:text-white transition-smooth flex items-center gap-2 border border-danger/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF
            </button>
            <button @click="exportExcel" class="bg-success/10 text-success px-4 py-2 rounded-xl text-sm font-semibold hover:bg-success hover:text-white transition-smooth flex items-center gap-2 border border-success/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                Export Excel
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-gray-50 p-4 rounded-xl mb-6 border border-gray-100 flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Mulai Tanggal</label>
            <input type="date" x-model="filter.start_date" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sampai Tanggal</label>
            <input type="date" x-model="filter.end_date" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div class="shrink-0 w-full md:w-auto">
            <button @click="fetchData" class="w-full md:w-auto px-6 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary/90 transition-smooth">
                Filter Data
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-y border-gray-100">
                    <th class="p-4 font-semibold rounded-tl-xl">Tanggal Penyaluran</th>
                    <th class="p-4 font-semibold">Koordinator/Masjid</th>
                    <th class="p-4 font-semibold">Barang Disalurkan</th>
                    <th class="p-4 font-semibold">No. Permintaan</th>
                    <th class="p-4 font-semibold rounded-tr-xl">Penerimaan Koor</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-gray-50/50 transition-smooth">
                        <td class="p-4 font-medium text-textDark" x-text="formatDate(item.tanggal_distribusi)"></td>
                        <td class="p-4">
                            <div class="font-bold text-primary" x-text="item.permintaan?.user?.name"></div>
                            <div class="text-xs text-gray-500" x-text="item.permintaan?.user?.nama_mesjid"></div>
                        </td>
                        <td class="p-4">
                            <ul class="list-disc list-inside text-gray-600 text-xs space-y-1">
                                <template x-for="detail in item.permintaan?.detail" :key="detail.id">
                                    <li x-text="(detail.barang?.nama_barang || '-') + ' (' + detail.jumlah + ' ' + (detail.barang?.satuan?.nama_satuan || '') + ')'"></li>
                                </template>
                            </ul>
                        </td>
                        <td class="p-4 text-gray-500" x-text="'PRM-' + item.permintaan_id.toString().padStart(4, '0')"></td>
                        <td class="p-4 text-xs font-medium">
                            <span x-show="item.bukti_terima" class="px-2 py-1 bg-success/10 text-success border border-success/20 rounded-lg">
                                Diterima Koor
                            </span>
                            <span x-show="!item.bukti_terima" class="px-2 py-1 bg-warning/10 text-warning border border-warning/20 rounded-lg">
                                Telah Disalurkan
                            </span>
                        </td>
                    </tr>
                </template>
                <tr x-show="items.length === 0">
                    <td colspan="6" class="p-8 text-center text-gray-500 font-medium">Belum ada data distribusi barang.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('laporanDistribusi', () => ({
            items: [],
            filter: {
                start_date: '',
                end_date: ''
            },
            
            init() {
                // Set default dates to current month
                const date = new Date();
                this.filter.start_date = new Date(date.getFullYear(), date.getMonth(), 1).toISOString().split('T')[0];
                this.filter.end_date = new Date(date.getFullYear(), date.getMonth() + 1, 0).toISOString().split('T')[0];
                
                this.fetchData();
            },
            
            fetchData() {
                const query = new URLSearchParams(this.filter).toString();
                fetch(`{{ route("admin.laporan.distribusi") }}?${query}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },

            exportPdf() {
                const query = new URLSearchParams(this.filter).toString();
                window.open(`{{ route('admin.laporan.distribusi.pdf') }}?${query}`, '_blank');
            },

            exportExcel() {
                const query = new URLSearchParams(this.filter).toString();
                window.location.href = `{{ route('admin.laporan.distribusi.excel') }}?${query}`;
            },

            formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
            }
        }));
    });
</script>
@endpush
@endsection
