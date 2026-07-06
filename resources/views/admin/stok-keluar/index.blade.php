@extends('layouts.app', ['title' => 'Barang Keluar'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="stokKeluarCrud()">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-textDark">Pencatatan Barang Keluar</h2>
        <button @click="openModal()" class="bg-danger text-white px-4 py-2 rounded-xl text-sm font-semibold shadow hover:bg-red-700 transition-smooth flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Catat Keluar
        </button>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border border-border">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-border">
                <tr>
                    <th class="px-6 py-4 font-semibold">Tanggal</th>
                    <th class="px-6 py-4 font-semibold">Nama Barang</th>
                    <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                    <th class="px-6 py-4 font-semibold">Keterangan / Tujuan</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <template x-for="item in items" :key="item.id_stok_keluar">
                    <tr class="hover:bg-gray-50/50 transition-smooth">
                        <td class="px-6 py-4 font-medium text-textDark" x-text="formatDate(item.tanggal)"></td>
                        <td class="px-6 py-4 text-text">
                            <span class="font-semibold text-textDark" x-text="item.barang?.nama_barang"></span>
                            <div class="text-xs text-gray-400 mt-0.5" x-text="item.barang?.kode_barang"></div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-danger/10 text-danger font-semibold border border-danger/20">
                                -<span x-text="item.jumlah"></span>
                                <span class="text-[10px] font-medium" x-text="item.barang?.satuan?.nama_satuan"></span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-text" x-text="item.keterangan || '-'"></td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button @click="openModal(item)" class="p-1.5 text-info hover:bg-info/10 rounded-lg transition-smooth border border-transparent hover:border-info/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="deleteData(item.id_stok_keluar)" class="p-1.5 text-danger hover:bg-danger/10 rounded-lg transition-smooth border border-transparent hover:border-danger/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="items.length === 0">
                    <td colspan="5" class="text-center py-10 text-gray-400">Belum ada catatan barang keluar.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Form -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4" x-transition.opacity>
        <div class="bg-card w-full max-w-md rounded-2xl shadow-xl border border-border overflow-hidden" @click.away="closeModal()" x-transition.scale.origin.bottom>
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-border flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-lg text-textDark" x-text="isEdit ? 'Edit Barang Keluar' : 'Catat Barang Keluar'"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-danger focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form @submit.prevent="saveData">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Pilih Barang</label>
                        <select x-model="form.barang_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id_barang }}">{{ $b->kode_barang }} - {{ $b->nama_barang }} (Sisa: {{ $b->stok }})</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-danger" x-text="errors.barang_id"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Keluar</label>
                            <input type="date" x-model="form.tanggal" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                            <span class="text-xs text-danger" x-text="errors.tanggal"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Jumlah</label>
                            <input type="number" x-model="form.jumlah" min="1" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                            <span class="text-xs text-danger" x-text="errors.jumlah"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Keterangan / Tujuan (Opsional)</label>
                        <textarea x-model="form.keterangan" rows="2" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm resize-none" placeholder="Misal: Dipakai untuk pembersihan jumat, rusak, dll"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-border bg-gray-50/50 flex justify-end gap-3">
                    <button type="button" @click="closeModal()" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-smooth">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-danger rounded-xl hover:bg-red-700 transition-smooth" x-text="isLoading ? 'Menyimpan...' : 'Simpan'" :disabled="isLoading"></button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('stokKeluarCrud', () => ({
            items: [],
            isModalOpen: false,
            isEdit: false,
            isLoading: false,
            editId: null,
            form: { barang_id: '', jumlah: 1, tanggal: new Date().toISOString().split('T')[0], keterangan: '' },
            errors: {},
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("admin.stok-keluar.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },

            formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            },
            
            openModal(item = null) {
                this.errors = {};
                if (item) {
                    this.isEdit = true;
                    this.editId = item.id_stok_keluar;
                    this.form = { ...item };
                } else {
                    this.isEdit = false;
                    this.editId = null;
                    this.form = { barang_id: '', jumlah: 1, tanggal: new Date().toISOString().split('T')[0], keterangan: '' };
                }
                this.isModalOpen = true;
            },
            
            closeModal() {
                this.isModalOpen = false;
            },
            
            saveData() {
                this.isLoading = true;
                this.errors = {};
                
                let url = '{{ route("admin.stok-keluar.store") }}';
                let method = 'POST';
                
                if (this.isEdit) {
                    url = `/admin/stok-keluar/${this.editId}`;
                    method = 'PUT';
                }
                
                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.form)
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(res => {
                    this.isLoading = false;
                    if (res.status === 422) {
                        for (let key in res.body.errors) {
                            this.errors[key] = res.body.errors[key][0];
                        }
                    } else if (res.status === 400) {
                        Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup!', text: res.body.message });
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
            },
            
            deleteData(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Menghapus data ini akan MENGEMBALIKAN stok barang seperti semula!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/stok-keluar/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json().then(data => ({ status: res.status, body: data })))
                        .then(res => {
                            if (res.status === 400) {
                                Swal.fire({ icon: 'error', title: 'Gagal!', text: res.body.message });
                            } else if (res.status === 200) {
                                Swal.fire({ icon: 'success', title: 'Terhapus!', text: res.body.message, confirmButtonColor: '#0F766E' });
                                this.fetchData();
                            } else {
                                throw new Error(res.body.message || 'Terjadi kesalahan server');
                            }
                        })
                        .catch(err => {
                            Swal.fire({ icon: 'error', title: 'Oops...', text: err.message || 'Gagal menghapus data' });
                        });
                    }
                });
            }
        }));
    });
</script>
@endpush
@endsection
