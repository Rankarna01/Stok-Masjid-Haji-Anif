@extends('layouts.app', ['title' => 'Data Barang'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="barangCrud()">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-textDark">Manajemen Data Barang</h2>
        <button @click="openModal()" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-semibold shadow hover:bg-teal-800 transition-smooth flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Barang
        </button>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border border-border">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-border">
                <tr>
                    <th class="px-6 py-4 font-semibold">Gambar</th>
                    <th class="px-6 py-4 font-semibold">Kode</th>
                    <th class="px-6 py-4 font-semibold">Nama Barang</th>
                    <th class="px-6 py-4 font-semibold">Kategori</th>
                    <th class="px-6 py-4 font-semibold">Stok</th>
                    <th class="px-6 py-4 font-semibold">Satuan</th>
                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-gray-50/50 transition-smooth">
                        <td class="px-6 py-4">
                            <template x-if="item.foto_barang">
                                <img :src="'/storage/' + item.foto_barang" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                            </template>
                            <template x-if="!item.foto_barang">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-400 text-xs font-medium">No Img</div>
                            </template>
                        </td>
                        <td class="px-6 py-4 font-medium text-primary" x-text="item.kode_barang"></td>
                        <td class="px-6 py-4 font-semibold text-textDark" x-text="item.nama_barang"></td>
                        <td class="px-6 py-4 text-text" x-text="item.kategori.nama_kategori"></td>
                        <td class="px-6 py-4 text-text font-bold" x-text="item.stok" :class="{'text-danger': item.stok <= 10}"></td>
                        <td class="px-6 py-4 text-text" x-text="item.satuan.nama_satuan"></td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button @click="openModal(item)" class="p-1.5 text-info hover:bg-info/10 rounded-lg transition-smooth border border-transparent hover:border-info/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="deleteData(item.id_barang)" class="p-1.5 text-danger hover:bg-danger/10 rounded-lg transition-smooth border border-transparent hover:border-danger/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="items.length === 0">
                    <td colspan="6" class="text-center py-10 text-gray-400">Belum ada data barang.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Form -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4" x-transition.opacity>
        <div class="bg-card w-full max-w-lg rounded-2xl shadow-xl border border-border overflow-hidden" @click.away="closeModal()" x-transition.scale.origin.bottom>
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-border flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-lg text-textDark" x-text="isEdit ? 'Edit Barang' : 'Tambah Barang'"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-danger focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form @submit.prevent="saveData">
                <div class="p-6 space-y-4">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kode Barang -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Kode Barang</label>
                            <input type="text" x-model="form.kode_barang" readonly :placeholder="isEdit ? '' : 'Auto Generated (BRG-...)'" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed outline-none text-sm">
                        </div>
                        
                        <!-- Nama Barang -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Barang</label>
                            <input type="text" x-model="form.nama_barang" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                            <span class="text-xs text-danger" x-text="errors.nama_barang"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kategori -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori</label>
                            <select x-model="form.kategori_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <span class="text-xs text-danger" x-text="errors.kategori_id"></span>
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Satuan</label>
                            <select x-model="form.satuan_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                                <option value="">Pilih Satuan</option>
                                @foreach($satuans as $s)
                                    <option value="{{ $s->id_satuan }}">{{ $s->nama_satuan }}</option>
                                @endforeach
                            </select>
                            <span class="text-xs text-danger" x-text="errors.satuan_id"></span>
                        </div>
                    </div>

                    <!-- Stok Awal -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Stok Awal</label>
                        <input type="number" x-model="form.stok" min="0" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                        <span class="text-xs text-danger" x-text="errors.stok"></span>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Keterangan (Opsional)</label>
                        <textarea x-model="form.keterangan" rows="2" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm resize-none"></textarea>
                    </div>

                    <!-- Foto Barang -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Foto Barang (Opsional)</label>
                        <input type="file" x-ref="foto_barang" accept="image/*" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        <span class="text-xs text-danger" x-text="errors.foto_barang"></span>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-border bg-gray-50/50 flex justify-end gap-3">
                    <button type="button" @click="closeModal()" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-smooth">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-xl hover:bg-teal-800 transition-smooth" x-text="isLoading ? 'Menyimpan...' : 'Simpan'" :disabled="isLoading"></button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('barangCrud', () => ({
            items: [],
            isModalOpen: false,
            isEdit: false,
            isLoading: false,
            editId: null,
            form: { kode_barang: '', nama_barang: '', kategori_id: '', satuan_id: '', stok: 0, keterangan: '' },
            errors: {},
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("admin.barang.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },
            
            openModal(item = null) {
                this.errors = {};
                if (item) {
                    this.isEdit = true;
                    this.editId = item.id_barang;
                    this.form = { ...item };
                } else {
                    this.isEdit = false;
                    this.editId = null;
                    this.form = { kode_barang: '', nama_barang: '', kategori_id: '', satuan_id: '', stok: 0, keterangan: '' };
                }
                if (this.$refs.foto_barang) this.$refs.foto_barang.value = '';
                this.isModalOpen = true;
            },
            
            closeModal() {
                this.isModalOpen = false;
            },
            
            saveData() {
                this.isLoading = true;
                this.errors = {};
                
                let url = '{{ route("admin.barang.store") }}';
                
                let formData = new FormData();
                formData.append('nama_barang', this.form.nama_barang);
                formData.append('kategori_id', this.form.kategori_id);
                formData.append('satuan_id', this.form.satuan_id);
                formData.append('stok', this.form.stok);
                if (this.form.keterangan) formData.append('keterangan', this.form.keterangan);
                
                let fileInput = this.$refs.foto_barang;
                if (fileInput && fileInput.files[0]) {
                    formData.append('foto_barang', fileInput.files[0]);
                }
                
                if (this.isEdit) {
                    url = `/admin/barang/${this.editId}`;
                    formData.append('_method', 'PUT');
                }
                
                fetch(url, {
                    method: 'POST', // Always POST when using FormData (Laravel will treat as PUT via _method)
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
                        // Validation errors
                        for (let key in res.body.errors) {
                            this.errors[key] = res.body.errors[key][0];
                        }
                    } else if (res.status === 200 || res.status === 201) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.body.message,
                            confirmButtonColor: '#0F766E'
                        });
                        this.closeModal();
                        this.fetchData();
                    } else {
                        throw new Error('Terjadi kesalahan server');
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
                    text: "Data barang ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/barang/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: data.message, confirmButtonColor: '#0F766E' });
                            this.fetchData();
                        })
                        .catch(err => {
                            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal menghapus data' });
                        });
                    }
                });
            }
        }));
    });
</script>
@endpush
@endsection
