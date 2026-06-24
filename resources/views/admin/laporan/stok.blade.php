@extends('layouts.app', ['title' => 'Laporan Stok Barang'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="laporanStok()">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-textDark">Laporan Stok Keseluruhan</h2>
            <p class="text-sm text-gray-500">Rekapitulasi sisa stok barang per hari ini.</p>
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
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pencarian</label>
            <input type="text" x-model="filter.search" placeholder="Cari nama atau kode barang..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kategori</label>
            <select x-model="filter.kategori_id" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="Semua">Semua Kategori</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="shrink-0 w-full md:w-auto">
            <button @click="fetchData(1)" class="w-full md:w-auto px-6 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary/90 transition-smooth">
                Filter Data
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-y border-gray-100">
                    <th class="p-4 font-semibold rounded-tl-xl">Tanggal Masuk</th>
                    <th class="p-4 font-semibold">Kode Barang</th>
                    <th class="p-4 font-semibold">Nama Barang</th>
                    <th class="p-4 font-semibold">Kategori</th>
                    <th class="p-4 font-semibold">Jumlah Masuk</th>
                    <th class="p-4 font-semibold">Sisa Stok</th>
                    <th class="p-4 font-semibold rounded-tr-xl">Satuan</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                <template x-for="(item, index) in items" :key="item.id">
                    <tr class="hover:bg-gray-50/50 transition-smooth">
                        <td class="p-4 text-gray-500" x-text="formatDate(item.tanggal)"></td>
                        <td class="p-4 font-medium text-textDark" x-text="item.barang?.kode_barang"></td>
                        <td class="p-4 font-bold text-primary" x-text="item.barang?.nama_barang"></td>
                        <td class="p-4 text-gray-600">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs" x-text="item.barang?.kategori?.nama_kategori || '-'"></span>
                        </td>
                        <td class="p-4">
                            <span class="font-bold text-success" x-text="'+ ' + item.jumlah"></span>
                        </td>
                        <td class="p-4">
                            <span class="font-bold" :class="item.barang?.stok <= 5 ? 'text-danger' : 'text-textDark'" x-text="item.barang?.stok"></span>
                        </td>
                        <td class="p-4 text-gray-500" x-text="item.barang?.satuan?.nama_satuan || '-'"></td>
                    </tr>
                </template>
                <tr x-show="items.length === 0">
                    <td colspan="7" class="p-8 text-center text-gray-500 font-medium">Belum ada data barang / riwayat stok masuk pada filter tersebut.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4" x-show="pagination.last_page > 1">
        <div class="text-sm text-gray-500">
            Menampilkan <span class="font-medium text-textDark" x-text="pagination.from || 0"></span> sampai <span class="font-medium text-textDark" x-text="pagination.to || 0"></span> dari <span class="font-medium text-textDark" x-text="pagination.total || 0"></span> data
        </div>
        <div class="flex gap-2">
            <button @click="fetchData(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-semibold hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-smooth">
                Sebelumnya
            </button>
            <button @click="fetchData(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-semibold hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-smooth">
                Selanjutnya
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('laporanStok', () => ({
            items: [],
            pagination: {},
            filter: {
                start_date: '',
                end_date: '',
                search: '',
                kategori_id: 'Semua'
            },
            
            init() {
                const date = new Date();
                this.filter.start_date = new Date(date.getFullYear(), date.getMonth(), 1).toISOString().split('T')[0];
                this.filter.end_date = new Date(date.getFullYear(), date.getMonth() + 1, 0).toISOString().split('T')[0];
                
                this.fetchData();
            },
            
            fetchData(page = 1) {
                if (typeof page !== 'number') page = 1;
                const query = new URLSearchParams(this.filter).toString();
                fetch(`{{ route("admin.laporan.stok") }}?page=${page}&${query}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { 
                    this.items = data.data; 
                    this.pagination = data;
                });
            },

            exportPdf() {
                const query = new URLSearchParams(this.filter).toString();
                window.open(`{{ route('admin.laporan.stok.pdf') }}?${query}`, '_blank');
            },

            exportExcel() {
                const query = new URLSearchParams(this.filter).toString();
                window.location.href = `{{ route('admin.laporan.stok.excel') }}?${query}`;
            },

            formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
            }
        }));
    });
</script>
@endpush
@endsection
