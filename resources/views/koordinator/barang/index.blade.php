@extends('layouts.app', ['title' => 'Daftar Barang'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="katalogBarang()">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-textDark">Katalog Barang Kebersihan</h2>
            <p class="text-sm text-gray-500">Daftar stok barang yang tersedia saat ini.</p>
        </div>
        <div class="relative w-full md:w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" x-model="search" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition-smooth" placeholder="Cari barang...">
        </div>
    </div>

    <!-- Grid View -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="item in filteredItems" :key="item.id_barang">
            <div class="bg-white border border-gray-100 rounded-2xl p-5 hover:shadow-lg transition-smooth hover:border-primary/30 flex flex-col h-full">
                <div class="flex justify-between items-start mb-4">
                    <template x-if="item.foto_barang">
                        <img :src="'/storage/' + item.foto_barang" class="w-12 h-12 rounded-xl object-cover shadow-sm border border-gray-100 shrink-0">
                    </template>
                    <template x-if="!item.foto_barang">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                    </template>
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase tracking-wider" x-text="item.kategori?.nama_kategori || '-'"></span>
                </div>
                
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-textDark mb-1" x-text="item.nama_barang"></h3>
                    <p class="text-xs text-gray-400 font-medium mb-4" x-text="item.kode_barang"></p>
                </div>

                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 block mb-0.5">Sisa Stok</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-xl font-bold" :class="item.stok <= 5 ? 'text-danger' : 'text-textDark'" x-text="item.stok"></span>
                            <span class="text-xs text-gray-500 font-medium" x-text="item.satuan?.nama_satuan || ''"></span>
                        </div>
                    </div>
                    
                    <a href="{{ route('koordinator.permintaan.create') }}" class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-smooth" title="Buat Permintaan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </a>
                </div>
            </div>
        </template>
    </div>
    
    <!-- Empty State -->
    <div x-show="filteredItems.length === 0" class="text-center py-16 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
        <h3 class="text-lg font-bold text-gray-700 mb-1">Barang tidak ditemukan</h3>
        <p class="text-sm text-gray-500">Coba gunakan kata kunci pencarian yang lain.</p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('katalogBarang', () => ({
            items: [],
            search: '',
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("koordinator.barang.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },

            get filteredItems() {
                if (this.search === '') return this.items;
                return this.items.filter(item => {
                    return item.nama_barang.toLowerCase().includes(this.search.toLowerCase()) || 
                           item.kode_barang.toLowerCase().includes(this.search.toLowerCase());
                });
            }
        }));
    });
</script>
@endpush
@endsection
