@extends('layouts.app', ['title' => 'Bukti Kondisi Barang'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="buktiKondisi()">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-textDark">Konfirmasi Penerimaan Barang</h2>
        <p class="text-sm text-gray-500 mt-1">Unggah foto kondisi barang yang Anda terima dari hasil penyaluran admin.</p>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-y border-gray-100">
                    <th class="p-4 font-semibold rounded-tl-xl">No. Permintaan</th>
                    <th class="p-4 font-semibold">Tgl. Disalurkan</th>
                    <th class="p-4 font-semibold">Barang yang Diterima</th>
                    <th class="p-4 font-semibold">Bukti dari Admin</th>
                    <th class="p-4 font-semibold rounded-tr-xl">Status Penerimaan</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-gray-50/50 transition-smooth">
                        <td class="p-4 font-medium text-textDark" x-text="'PRM-' + item.permintaan.id.toString().padStart(4, '0')"></td>
                        <td class="p-4 text-gray-500" x-text="formatDate(item.tanggal_distribusi)"></td>
                        <td class="p-4">
                            <ul class="list-disc list-inside text-gray-600 text-xs space-y-1">
                                <template x-for="detail in item.permintaan.detail" :key="detail.id">
                                    <li x-text="(detail.barang?.nama_barang || '-') + ' (' + detail.jumlah + ' ' + (detail.barang?.satuan?.nama_satuan || '') + ')'"></li>
                                </template>
                            </ul>
                        </td>
                        <td class="p-4">
                            <a :href="'/storage/' + item.dokumentasi" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition-smooth flex items-center w-max gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Lihat Bukti Penyaluran
                            </a>
                        </td>
                        <td class="p-4">
                            <template x-if="item.bukti_terima">
                                <div>
                                    <span class="inline-flex px-2 py-1 bg-success/10 text-success rounded-lg text-xs font-bold mb-2">Telah Diterima</span>
                                    <a :href="'/storage/' + item.bukti_terima" target="_blank" class="text-xs text-primary hover:underline block">Lihat Kondisi Barang</a>
                                </div>
                            </template>
                            
                            <template x-if="!item.bukti_terima">
                                <button @click="openModal(item)" class="px-4 py-2 bg-primary text-white rounded-xl text-xs font-semibold hover:bg-primary/90 transition-smooth shadow-sm">
                                    Konfirmasi Terima
                                </button>
                            </template>
                        </td>
                    </tr>
                </template>
                <tr x-show="items.length === 0">
                    <td colspan="5" class="p-8 text-center text-gray-500 font-medium">Belum ada barang yang didistribusikan.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Konfirmasi Terima -->
    <div x-show="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="closeModal()"></div>

            <div x-show="isModalOpen" x-transition.scale class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-textDark">Konfirmasi Barang Diterima</h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm">
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500">Silakan unggah foto bukti bahwa barang telah Anda terima. Pastikan foto menunjukkan kondisi barang dengan jelas.</p>
                        
                        <div>
                            <label class="block text-sm font-bold text-textDark mb-1">Foto Bukti / Kondisi Barang</label>
                            <input type="file" x-ref="buktiFile" accept="image/png, image/jpeg" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-smooth border border-gray-200 rounded-xl p-2">
                            <p class="text-[10px] text-gray-400 mt-1">Maksimal 2MB (JPG, PNG)</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition-smooth">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-primary hover:bg-primary/90 rounded-xl transition-smooth flex items-center gap-2" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}">
                            <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Unggah Bukti'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('buktiKondisi', () => ({
            items: [],
            isModalOpen: false,
            isSubmitting: false,
            selectedItem: null,
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("koordinator.bukti-terima.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },

            formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
            },

            openModal(item) {
                this.selectedItem = item;
                this.isModalOpen = true;
                if(this.$refs.buktiFile) this.$refs.buktiFile.value = '';
            },

            closeModal() {
                this.isModalOpen = false;
                this.selectedItem = null;
            },

            submitForm() {
                if (!this.selectedItem) return;
                
                const fileInput = this.$refs.buktiFile;
                if (!fileInput.files.length) {
                    Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pilih file foto bukti terima terlebih dahulu!' });
                    return;
                }

                this.isSubmitting = true;
                
                const formData = new FormData();
                formData.append('bukti_terima', fileInput.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                fetch(`/koordinator/bukti-terima/${this.selectedItem.id}`, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(res => {
                    this.isSubmitting = false;
                    if (res.status === 200) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.body.message, showConfirmButton: false, timer: 1500 });
                        this.closeModal();
                        this.fetchData();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: res.body.message || 'Terjadi kesalahan sistem.' });
                    }
                })
                .catch(err => {
                    this.isSubmitting = false;
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi bermasalah.' });
                });
            }
        }));
    });
</script>
@endpush
@endsection
