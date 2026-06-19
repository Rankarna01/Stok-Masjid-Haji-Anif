@extends('layouts.app', ['title' => 'Data Koordinator'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="koordinatorCrud()">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-textDark">Manajemen Data Koordinator</h2>
        <button @click="openModal()" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-semibold shadow hover:bg-teal-800 transition-smooth flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Koordinator
        </button>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border border-border">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-border">
                <tr>
                    <th class="px-6 py-4 font-semibold">Nama Koordinator</th>
                    <th class="px-6 py-4 font-semibold">Wilayah</th>
                    <th class="px-6 py-4 font-semibold">Kontak</th>
                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-gray-50/50 transition-smooth">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                    <span x-text="item.name.charAt(0)"></span>
                                </div>
                                <div>
                                    <p class="font-semibold text-textDark" x-text="item.name"></p>
                                    <p class="text-[11px] text-gray-500" x-text="item.email"></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-text" x-text="item.nama_mesjid"></td>
                        <td class="px-6 py-4 text-text" x-text="item.no_hp"></td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button @click="openModal(item)" class="p-1.5 text-info hover:bg-info/10 rounded-lg transition-smooth border border-transparent hover:border-info/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="deleteData(item.id)" class="p-1.5 text-danger hover:bg-danger/10 rounded-lg transition-smooth border border-transparent hover:border-danger/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="items.length === 0">
                    <td colspan="4" class="text-center py-10 text-gray-400">Belum ada data koordinator.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Form -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4" x-transition.opacity>
        <div class="bg-card w-full max-w-lg rounded-2xl shadow-xl border border-border overflow-hidden" @click.away="closeModal()" x-transition.scale.origin.bottom>
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-border flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-lg text-textDark" x-text="isEdit ? 'Edit Koordinator' : 'Tambah Koordinator'"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-danger focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form @submit.prevent="saveData">
                <div class="p-6 space-y-4">
                    <!-- Name & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Lengkap</label>
                            <input type="text" x-model="form.name" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                            <span class="text-xs text-danger" x-text="errors.name"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                            <input type="email" x-model="form.email" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                            <span class="text-xs text-danger" x-text="errors.email"></span>
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">
                            Password <span x-show="isEdit" class="text-gray-400 font-normal">(Kosongkan jika tidak ingin diubah)</span>
                        </label>
                        <input type="password" x-model="form.password" :required="!isEdit" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                        <span class="text-xs text-danger" x-text="errors.password"></span>
                    </div>

                    <!-- Mesjid & No HP -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Wilayah</label>
                            <input type="text" x-model="form.nama_mesjid" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                            <span class="text-xs text-danger" x-text="errors.nama_mesjid"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">No. HP / WA</label>
                            <input type="text" x-model="form.no_hp" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                            <span class="text-xs text-danger" x-text="errors.no_hp"></span>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Alamat Wilayah</label>
                        <textarea x-model="form.alamat" rows="2" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm resize-none"></textarea>
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
        Alpine.data('koordinatorCrud', () => ({
            items: [],
            isModalOpen: false,
            isEdit: false,
            isLoading: false,
            editId: null,
            form: { name: '', email: '', password: '', nama_mesjid: '', no_hp: '', alamat: '' },
            errors: {},
            
            init() {
                this.fetchData();
            },
            
            fetchData() {
                fetch('{{ route("admin.koordinator.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { this.items = data; });
            },
            
            openModal(item = null) {
                this.errors = {};
                if (item) {
                    this.isEdit = true;
                    this.editId = item.id;
                    this.form = { ...item, password: '' };
                } else {
                    this.isEdit = false;
                    this.editId = null;
                    this.form = { name: '', email: '', password: '', nama_mesjid: '', no_hp: '', alamat: '' };
                }
                this.isModalOpen = true;
            },
            
            closeModal() {
                this.isModalOpen = false;
            },
            
            saveData() {
                this.isLoading = true;
                this.errors = {};
                
                let url = '{{ route("admin.koordinator.store") }}';
                let method = 'POST';
                
                if (this.isEdit) {
                    url = `/admin/koordinator/${this.editId}`;
                    method = 'PUT';
                }
                
                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.form)
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
                    text: "Data koordinator ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/koordinator/${id}`, {
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
