@extends('layouts.app', ['title' => 'Riwayat Stok'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="riwayatStok()">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-textDark">Riwayat Pergerakan Stok</h2>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border border-border">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-border">
                <tr>
                    <th class="px-6 py-4 font-semibold">Tanggal</th>
                    <th class="px-6 py-4 font-semibold">Nama Barang</th>
                    <th class="px-6 py-4 font-semibold text-center">Jenis Transaksi</th>
                    <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                    <th class="px-6 py-4 font-semibold">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <template x-for="item in items" :key="item.jenis + '-' + item.id">
                    <tr class="hover:bg-gray-50/50 transition-smooth">
                        <td class="px-6 py-4 font-medium text-textDark" x-text="formatDate(item.tanggal)"></td>
                        <td class="px-6 py-4 text-text">
                            <span class="font-semibold text-textDark" x-text="item.barang?.nama_barang"></span>
                            <div class="text-xs text-gray-400 mt-0.5" x-text="item.barang?.kode_barang"></div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span x-show="item.jenis === 'masuk'" class="px-3 py-1 rounded-full text-xs font-semibold bg-success/10 text-success border border-success/20">Barang Masuk</span>
                            <span x-show="item.jenis === 'keluar'" class="px-3 py-1 rounded-full text-xs font-semibold bg-danger/10 text-danger border border-danger/20">Barang Keluar</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg font-semibold border"
                                  :class="item.jenis === 'masuk' ? 'bg-success/10 text-success border-success/20' : 'bg-danger/10 text-danger border-danger/20'">
                                <span x-text="(item.jenis === 'masuk' ? '+' : '-') + item.jumlah"></span>
                                <span class="text-[10px] font-medium" x-text="item.barang?.satuan?.nama_satuan"></span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-text" x-text="item.keterangan || '-'"></td>
                    </tr>
                </template>
                <tr x-show="items.length === 0">
                    <td colspan="5" class="text-center py-10 text-gray-400">Belum ada riwayat pergerakan stok.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('riwayatStok', () => ({
            items: [],
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("admin.riwayat-stok.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },

            formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            }
        }));
    });
</script>
@endpush
@endsection
