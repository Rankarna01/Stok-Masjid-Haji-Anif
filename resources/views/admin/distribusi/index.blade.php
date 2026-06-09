@extends('layouts.app', ['title' => 'Distribusi Barang'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="distribusiCrud()">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-textDark">Penyaluran / Distribusi Barang</h2>
        <p class="text-sm text-gray-500 mt-1">Catat dan unggah bukti dokumentasi untuk setiap permintaan yang telah disetujui.</p>
    </div>

    <!-- Filter (Optional) -->
    <div class="flex gap-2 mb-6 border-b border-gray-100 pb-4">
        <button @click="filter = 'Semua'" :class="filter === 'Semua' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-smooth">Semua</button>
        <button @click="filter = 'Belum'" :class="filter === 'Belum' ? 'bg-warning text-white' : 'bg-warning/10 text-warning hover:bg-warning/20'" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-smooth">Menunggu Penyaluran</button>
        <button @click="filter = 'Selesai'" :class="filter === 'Selesai' ? 'bg-success text-white' : 'bg-success/10 text-success hover:bg-success/20'" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-smooth">Selesai Didistribusikan</button>
    </div>

    <!-- List -->
    <div class="space-y-4">
        <template x-for="item in filteredItems" :key="item.id">
            <div class="border border-border rounded-2xl p-5 bg-white shadow-sm hover:shadow-md transition-smooth">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-md" x-text="'PRM-' + item.id.toString().padStart(4, '0')"></span>
                            <span class="text-xs font-medium text-gray-500" x-text="'Disetujui pada: ' + formatDate(item.tanggal)"></span>
                        </div>
                        <div class="text-base font-bold text-textDark mt-2">
                            Koordinator: <span x-text="item.user?.nama_mesjid || item.user?.name"></span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:items-end gap-3">
                        <template x-if="!item.distribusi">
                            <button @click="openModal(item)" class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-primary text-white hover:bg-primary/90 transition-smooth shadow-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                Catat Penyaluran
                            </button>
                        </template>

                        <template x-if="item.distribusi">
                            <div class="flex flex-col items-end gap-2">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full border bg-success/10 text-success border-success/20 inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Telah Disalurkan
                                </span>
                                <div class="text-[10px] text-gray-500 font-medium" x-text="'Tgl: ' + formatDate(item.distribusi.tanggal_distribusi)"></div>
                                <template x-if="item.distribusi.dokumentasi">
                                    <a :href="'/storage/' + item.distribusi.dokumentasi" target="_blank" class="text-[10px] font-semibold text-primary hover:underline flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat Dokumentasi
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Barang yang Disalurkan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <template x-for="detail in item.detail" :key="detail.id">
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <div class="text-sm font-semibold text-textDark" x-text="detail.barang?.nama_barang"></div>
                                <div class="font-bold text-sm text-primary" x-text="detail.jumlah + ' ' + (detail.barang?.satuan?.nama_satuan || '')"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="filteredItems.length === 0" class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            <p class="text-gray-500 font-medium">Tidak ada data distribusi dengan filter tersebut.</p>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4" x-transition.opacity>
        <div class="bg-card w-full max-w-md rounded-2xl shadow-xl border border-border overflow-hidden" @click.away="closeModal()" x-transition.scale.origin.bottom>
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-border flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-lg text-textDark">Catat Penyaluran Barang</h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-danger focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form @submit.prevent="saveData">
                <div class="p-6 space-y-4">
                    <div class="bg-info/10 p-3 rounded-xl border border-info/20 mb-4">
                        <p class="text-xs text-info font-medium leading-relaxed">
                            Mencatat penyaluran berarti barang fisik sudah Anda berikan ke koordinator. 
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Penyaluran</label>
                        <input type="date" x-model="form.tanggal_distribusi" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Upload Bukti Dokumentasi / Foto</label>
                        <input type="file" x-ref="dokumentasi" accept="image/jpeg, image/png, image/jpg" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                        <p class="text-[10px] text-gray-400 mt-1">Format: JPG/PNG. Max: 2MB.</p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-border bg-gray-50/50 flex justify-end gap-3">
                    <button type="button" @click="closeModal()" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-smooth">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-xl hover:bg-primary/90 transition-smooth" :disabled="isLoading">
                        <span x-show="!isLoading">Simpan Distribusi</span>
                        <span x-show="isLoading">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('distribusiCrud', () => ({
            items: [],
            filter: 'Semua', // Semua, Belum, Selesai
            isModalOpen: false,
            isLoading: false,
            form: { permintaan_id: null, tanggal_distribusi: new Date().toISOString().split('T')[0] },
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("admin.distribusi.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },

            get filteredItems() {
                if (this.filter === 'Semua') return this.items;
                if (this.filter === 'Belum') return this.items.filter(item => !item.distribusi);
                if (this.filter === 'Selesai') return this.items.filter(item => item.distribusi);
                return this.items;
            },

            formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            },

            openModal(item) {
                this.form.permintaan_id = item.id;
                this.form.tanggal_distribusi = new Date().toISOString().split('T')[0];
                if (this.$refs.dokumentasi) this.$refs.dokumentasi.value = '';
                this.isModalOpen = true;
            },

            closeModal() {
                this.isModalOpen = false;
            },

            saveData() {
                this.isLoading = true;
                const fileInput = this.$refs.dokumentasi;
                
                let formData = new FormData();
                formData.append('permintaan_id', this.form.permintaan_id);
                formData.append('tanggal_distribusi', this.form.tanggal_distribusi);
                
                if (fileInput.files.length > 0) {
                    formData.append('dokumentasi', fileInput.files[0]);
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: 'Silakan upload bukti dokumentasi' });
                    this.isLoading = false;
                    return;
                }

                fetch('{{ route("admin.distribusi.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(res => {
                    this.isLoading = false;
                    if (res.status === 422) {
                        let errors = '';
                        for(let key in res.body.errors) {
                            errors += res.body.errors[key][0] + '\n';
                        }
                        Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: errors });
                    } else if (res.status === 200 || res.status === 201) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.body.message, confirmButtonColor: '#0F766E' });
                        this.closeModal();
                        this.fetchData();
                    } else {
                        throw new Error(res.body.message || 'Terjadi kesalahan server');
                    }
                })
                .catch(err => {
                    this.isLoading = false;
                    Swal.fire({ icon: 'error', title: 'Oops...', text: err.message });
                });
            }
        }));
    });
</script>
@endpush
@endsection
