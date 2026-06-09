@extends('layouts.app', ['title' => 'Laporan Stok Barang'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="laporanStok()">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-textDark">Laporan Stok Keseluruhan</h2>
            <p class="text-sm text-gray-500">Rekapitulasi sisa stok barang per hari ini.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.laporan.stok.pdf') }}" target="_blank" class="bg-danger/10 text-danger px-4 py-2 rounded-xl text-sm font-semibold hover:bg-danger hover:text-white transition-smooth flex items-center gap-2 border border-danger/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF
            </a>
            <a href="{{ route('admin.laporan.stok.excel') }}" class="bg-success/10 text-success px-4 py-2 rounded-xl text-sm font-semibold hover:bg-success hover:text-white transition-smooth flex items-center gap-2 border border-success/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-y border-gray-100">
                    <th class="p-4 font-semibold rounded-tl-xl">No</th>
                    <th class="p-4 font-semibold">Kode Barang</th>
                    <th class="p-4 font-semibold">Nama Barang</th>
                    <th class="p-4 font-semibold">Kategori</th>
                    <th class="p-4 font-semibold">Stok</th>
                    <th class="p-4 font-semibold rounded-tr-xl">Satuan</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                <template x-for="(item, index) in items" :key="item.id">
                    <tr class="hover:bg-gray-50/50 transition-smooth">
                        <td class="p-4 text-gray-500" x-text="index + 1"></td>
                        <td class="p-4 font-medium text-textDark" x-text="item.kode_barang"></td>
                        <td class="p-4 font-bold text-primary" x-text="item.nama_barang"></td>
                        <td class="p-4 text-gray-600">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs" x-text="item.kategori?.nama_kategori || '-'"></span>
                        </td>
                        <td class="p-4">
                            <span class="font-bold" :class="item.stok <= 5 ? 'text-danger' : 'text-success'" x-text="item.stok"></span>
                        </td>
                        <td class="p-4 text-gray-500" x-text="item.satuan?.nama_satuan || '-'"></td>
                    </tr>
                </template>
                <tr x-show="items.length === 0">
                    <td colspan="6" class="p-8 text-center text-gray-500 font-medium">Belum ada data barang.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('laporanStok', () => ({
            items: [],
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("admin.laporan.stok") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            }
        }));
    });
</script>
@endpush
@endsection
