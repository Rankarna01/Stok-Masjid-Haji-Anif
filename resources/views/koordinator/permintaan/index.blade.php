@extends('layouts.app', ['title' => 'Riwayat Permintaan'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="riwayatPermintaan()">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-textDark">Riwayat Pengajuan Permintaan</h2>
        <a href="{{ route('koordinator.permintaan.create') }}" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-semibold shadow hover:bg-primary/90 transition-smooth flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Permintaan Baru
        </a>
    </div>

    <!-- Timeline / List -->
    <div class="space-y-4">
        <template x-for="item in items" :key="item.id_permintaan">
            <div class="border border-border rounded-2xl p-5 bg-white shadow-sm hover:shadow-md transition-smooth">
                <div class="flex justify-between items-start border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <div class="text-xs text-gray-500 font-semibold mb-1" x-text="'No: PRM-' + item.id_permintaan.toString().padStart(4, '0')"></div>
                        <div class="text-sm font-medium text-textDark" x-text="formatDate(item.tanggal)"></div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <div class="flex gap-2">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full border"
                                :class="{
                                    'bg-warning/10 text-warning border-warning/20': item.status === 'Menunggu',
                                    'bg-success/10 text-success border-success/20': item.status === 'Disetujui',
                                    'bg-danger/10 text-danger border-danger/20': item.status === 'Ditolak'
                                }"
                                x-text="item.status"></span>
                            
                            <!-- Tombol Batal untuk Menunggu -->
                            <button x-show="item.status === 'Menunggu'" @click="batalPermintaan(item.id_permintaan)" class="px-3 py-1 text-xs font-semibold rounded-full border bg-danger text-white border-danger hover:bg-red-700 transition-smooth">
                                Batal
                            </button>
                        </div>
                        
                        <!-- Lihat Bukti Distribusi -->
                        <template x-if="item.distribusi && item.distribusi.dokumentasi">
                            <a :href="'/storage/' + item.distribusi.dokumentasi" target="_blank" class="px-3 py-1 text-xs font-semibold text-primary border border-primary/20 rounded-full bg-primary/5 hover:bg-primary/10 transition-smooth flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Lihat Bukti Penyaluran
                            </a>
                        </template>
                    </div>
                </div>

                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rincian Barang Diminta</h4>
                    <ul class="space-y-2">
                        <template x-for="detail in item.detail" :key="detail.id_permintaan_detail">
                            <li class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-textDark" x-text="detail.barang?.nama_barang"></div>
                                        <div class="text-xs text-gray-500" x-text="detail.alasan ? 'Alasan: ' + detail.alasan : 'Tanpa alasan spesifik'"></div>
                                        <div x-show="detail.bukti_permintaan" class="mt-1">
                                            <a :href="'/storage/' + detail.bukti_permintaan" target="_blank" class="text-[10px] text-primary hover:underline flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                Lihat Bukti Lampiran
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="font-bold text-sm text-textDark bg-white px-3 py-1 rounded-lg border border-gray-200 shadow-sm" x-text="detail.jumlah + ' ' + (detail.barang?.satuan?.nama_satuan || '')"></div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </template>

        <div x-show="items.length === 0" class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <p class="text-gray-500 font-medium">Belum ada riwayat permintaan barang.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('riwayatPermintaan', () => ({
            items: [],
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("koordinator.permintaan.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },

            formatDate(dateString) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            },

            batalPermintaan(id) {
                Swal.fire({
                    title: 'Batalkan Permintaan?',
                    text: "Permintaan yang dibatalkan akan dihapus secara permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Tutup'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/koordinator/permintaan/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json().then(data => ({ status: res.status, body: data })))
                        .then(res => {
                            if (res.status === 200) {
                                Swal.fire({ icon: 'success', title: 'Dibatalkan!', text: res.body.message, confirmButtonColor: '#0F766E' });
                                this.fetchData();
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal!', text: res.body.message });
                            }
                        });
                    }
                });
            }
        }));
    });
</script>
@endpush
@endsection
