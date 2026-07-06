@extends('layouts.app', ['title' => 'Validasi Permintaan'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="validasiPermintaan()">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-textDark">Validasi Permintaan Barang</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola dan proses permintaan barang dari semua koordinator wilayah.</p>
    </div>

    <!-- Filter (Optional) -->
    <div class="flex gap-2 mb-6 border-b border-gray-100 pb-4">
        <button @click="filter = 'Semua'" :class="filter === 'Semua' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-smooth">Semua</button>
        <button @click="filter = 'Menunggu'" :class="filter === 'Menunggu' ? 'bg-warning text-white' : 'bg-warning/10 text-warning hover:bg-warning/20'" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-smooth">Menunggu</button>
        <button @click="filter = 'Disetujui'" :class="filter === 'Disetujui' ? 'bg-success text-white' : 'bg-success/10 text-success hover:bg-success/20'" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-smooth">Disetujui</button>
        <button @click="filter = 'Ditolak'" :class="filter === 'Ditolak' ? 'bg-danger text-white' : 'bg-danger/10 text-danger hover:bg-danger/20'" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-smooth">Ditolak</button>
    </div>

    <!-- List Permintaan -->
    <div class="space-y-4">
        <template x-for="item in filteredItems" :key="item.id_permintaan">
            <div class="border border-border rounded-2xl p-5 bg-white shadow-sm hover:shadow-md transition-smooth">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-md" x-text="'PRM-' + item.id_permintaan.toString().padStart(4, '0')"></span>
                            <span class="text-sm font-medium text-textDark" x-text="formatDate(item.tanggal)"></span>
                        </div>
                        <div class="text-base font-bold text-textDark mt-2">
                            Koordinator: <span x-text="item.user?.nama_mesjid || item.user?.name"></span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:items-end gap-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border inline-block text-center"
                            :class="{
                                'bg-warning/10 text-warning border-warning/20': item.status === 'Menunggu',
                                'bg-success/10 text-success border-success/20': item.status === 'Disetujui',
                                'bg-danger/10 text-danger border-danger/20': item.status === 'Ditolak'
                            }"
                            x-text="item.status"></span>
                        
                        <div class="flex gap-2" x-show="item.status === 'Menunggu'">
                            <button @click="prosesPermintaan(item.id_permintaan, 'Disetujui')" class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-success text-white hover:bg-green-700 transition-smooth shadow-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui
                            </button>
                            <button @click="prosesPermintaan(item.id_permintaan, 'Ditolak')" class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-white border border-danger text-danger hover:bg-danger/10 transition-smooth shadow-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Tolak
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rincian Barang</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <template x-for="detail in item.detail" :key="detail.id_permintaan_detail">
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <template x-if="!detail.bukti_permintaan">
                                        <div class="w-10 h-10 shrink-0 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-primary">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                    </template>
                                    <template x-if="detail.bukti_permintaan">
                                        <a :href="'/storage/' + detail.bukti_permintaan" target="_blank" class="shrink-0 group relative block">
                                            <img :src="'/storage/' + detail.bukti_permintaan" alt="Bukti" class="w-10 h-10 object-cover rounded-lg border border-gray-200 shadow-sm group-hover:opacity-75 transition-smooth">
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-smooth">
                                                <svg class="w-4 h-4 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </div>
                                        </a>
                                    </template>
                                    <div>
                                        <div class="text-sm font-semibold text-textDark" x-text="detail.barang?.nama_barang"></div>
                                        <div class="text-[10px] text-gray-400">
                                            Sisa Stok Gudang: <strong :class="detail.barang?.stok < detail.jumlah ? 'text-danger' : 'text-success'" x-text="detail.barang?.stok"></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="font-bold text-sm text-textDark bg-white px-3 py-1 rounded-lg border border-gray-200 shadow-sm text-right">
                                    <div x-text="detail.jumlah + ' ' + (detail.barang?.satuan?.nama_satuan || '')"></div>
                                    <div x-show="detail.alasan" class="text-[10px] text-gray-400 font-normal mt-0.5 max-w-[100px] truncate" :title="detail.alasan" x-text="detail.alasan"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="filteredItems.length === 0" class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <p class="text-gray-500 font-medium">Tidak ada permintaan dengan status tersebut.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('validasiPermintaan', () => ({
            items: [],
            filter: 'Semua', // Semua, Menunggu, Disetujui, Ditolak
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("admin.validasi-permintaan.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },

            get filteredItems() {
                if (this.filter === 'Semua') {
                    return this.items;
                }
                return this.items.filter(item => item.status === this.filter);
            },

            formatDate(dateString) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            },

            prosesPermintaan(id, status) {
                const actionText = status === 'Disetujui' ? 'menyetujui' : 'menolak';
                const confirmColor = status === 'Disetujui' ? '#0F766E' : '#EF4444';

                let warningText = `Anda akan ${actionText} permintaan ini.`;
                if (status === 'Disetujui') {
                    warningText += " Stok barang akan langsung dipotong secara otomatis.";
                }

                Swal.fire({
                    title: 'Konfirmasi ' + status,
                    text: warningText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, ' + status + '!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/validasi-permintaan/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ status: status })
                        })
                        .then(res => res.json().then(data => ({ status: res.status, body: data })))
                        .then(res => {
                            if (res.status === 200) {
                                Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.body.message, confirmButtonColor: '#0F766E' });
                                this.fetchData();
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal!', text: res.body.message });
                            }
                        })
                        .catch(err => {
                            Swal.fire({ icon: 'error', title: 'Oops...', text: err.message || 'Terjadi kesalahan' });
                        });
                    }
                });
            }
        }));
    });
</script>
@endpush
@endsection
