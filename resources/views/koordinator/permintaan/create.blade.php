@extends('layouts.app', ['title' => 'Buat Permintaan'])

@section('content')
<div class="bg-card rounded-2xl p-6 card-shadow border border-border" x-data="buatPermintaan()">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-textDark">Ajukan Permintaan Barang</h2>
        <p class="text-sm text-gray-500 mt-1">Pilih barang yang dibutuhkan untuk wilayah Anda. Anda dapat menambahkan beberapa barang sekaligus.</p>
    </div>

    <form @submit.prevent="submitPermintaan">
        <!-- Input Area -->
        <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-200 mb-6 flex flex-col md:flex-row gap-4 items-end flex-wrap">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Pilih Barang</label>
                <select x-model="currentItem.barang_id" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barangs as $b)
                        <option value="{{ $b->id }}" data-nama="{{ $b->nama_barang }}" data-satuan="{{ $b->satuan->nama_satuan ?? '' }}" data-stok="{{ $b->stok }}">
                            {{ $b->kode_barang }} - {{ $b->nama_barang }} (Sisa Stok: {{ $b->stok }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Jumlah</label>
                <input type="number" x-model="currentItem.jumlah" min="1" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Alasan (Opsional)</label>
                <input type="text" x-model="currentItem.alasan" placeholder="Misal: Untuk jumat bersih" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Bukti (Opsional)</label>
                <input type="file" x-ref="buktiFile" @change="handleFile($event)" accept="image/*" class="w-full px-4 py-1.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>
            <div class="w-full md:w-auto">
                <button type="button" @click="addItem()" class="w-full px-4 py-2 bg-primary/10 text-primary font-semibold rounded-xl border border-primary/20 hover:bg-primary/20 transition-smooth flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah
                </button>
            </div>
        </div>

        <!-- Keranjang List -->
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Keranjang Permintaan</h3>
        <div class="border border-border rounded-xl overflow-hidden mb-6">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-border">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Barang</th>
                        <th class="px-6 py-3 font-semibold text-center">Jumlah</th>
                        <th class="px-6 py-3 font-semibold">Alasan</th>
                        <th class="px-6 py-3 font-semibold text-center">Bukti</th>
                        <th class="px-6 py-3 font-semibold text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="hover:bg-gray-50/50 transition-smooth">
                            <td class="px-6 py-3 font-medium text-textDark" x-text="item.nama_barang"></td>
                            <td class="px-6 py-3 text-center font-bold text-primary">
                                <span x-text="item.jumlah"></span> <span class="text-xs font-medium text-gray-500" x-text="item.satuan"></span>
                            </td>
                            <td class="px-6 py-3 text-text" x-text="item.alasan || '-'"></td>
                            <td class="px-6 py-3 text-center">
                                <span x-show="item.bukti" class="px-2 py-1 text-[10px] font-semibold bg-primary/10 text-primary rounded-lg">Terlampir</span>
                                <span x-show="!item.bukti" class="text-xs text-gray-400">-</span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <button type="button" @click="removeItem(index)" class="p-1.5 text-danger hover:bg-danger/10 rounded-lg transition-smooth border border-transparent hover:border-danger/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="items.length === 0">
                        <td colspan="4" class="text-center py-6 text-gray-400">Keranjang masih kosong. Tambahkan barang di atas.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-3 pt-4 border-t border-border">
            <a href="{{ route('koordinator.permintaan.index') }}" class="px-6 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-smooth">Batal</a>
            <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-primary rounded-xl hover:bg-primary/90 transition-smooth flex items-center gap-2 shadow-sm" :disabled="items.length === 0 || isLoading" :class="{'opacity-50 cursor-not-allowed': items.length === 0 || isLoading}">
                <svg x-show="!isLoading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <svg x-show="isLoading" class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span x-text="isLoading ? 'Memproses...' : 'Ajukan Permintaan'"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('buatPermintaan', () => ({
            items: [],
            isLoading: false,
            currentItem: { barang_id: '', jumlah: 1, alasan: '', nama_barang: '', satuan: '', stok: 0, bukti: null },
            
            handleFile(event) {
                const file = event.target.files[0];
                this.currentItem.bukti = file || null;
            },

            addItem() {
                if (!this.currentItem.barang_id) {
                    Swal.fire({ icon: 'warning', title: 'Pilih Barang', text: 'Silakan pilih barang terlebih dahulu.' });
                    return;
                }
                
                if (this.currentItem.jumlah < 1) {
                    Swal.fire({ icon: 'warning', title: 'Jumlah Tidak Valid', text: 'Jumlah barang minimal 1.' });
                    return;
                }

                // Ambil data text dari select option yang dipilih
                const selectEl = document.querySelector(`select[x-model="currentItem.barang_id"]`);
                const selectedOption = selectEl.options[selectEl.selectedIndex];
                
                this.currentItem.nama_barang = selectedOption.dataset.nama;
                this.currentItem.satuan = selectedOption.dataset.satuan;
                this.currentItem.stok = parseInt(selectedOption.dataset.stok);

                if (this.currentItem.jumlah > this.currentItem.stok) {
                    Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup', text: `Sisa stok untuk ${this.currentItem.nama_barang} hanya ${this.currentItem.stok}.` });
                    return;
                }

                // Cek apakah barang sudah ada di keranjang
                const existingIndex = this.items.findIndex(item => item.barang_id === this.currentItem.barang_id);
                if (existingIndex > -1) {
                    // Update jumlah jika sudah ada
                    const newTotal = parseInt(this.items[existingIndex].jumlah) + parseInt(this.currentItem.jumlah);
                    if (newTotal > this.currentItem.stok) {
                        Swal.fire({ icon: 'error', title: 'Melebihi Stok', text: `Total permintaan ${this.currentItem.nama_barang} di keranjang melebihi stok yang ada.` });
                        return;
                    }
                    this.items[existingIndex].jumlah = newTotal;
                    if(this.currentItem.alasan) {
                         this.items[existingIndex].alasan = this.currentItem.alasan;
                    }
                    if(this.currentItem.bukti) {
                         this.items[existingIndex].bukti = this.currentItem.bukti;
                    }
                } else {
                    // Tambah baru
                    this.items.push({ ...this.currentItem });
                }

                // Reset form input
                this.currentItem = { barang_id: '', jumlah: 1, alasan: '', nama_barang: '', satuan: '', stok: 0, bukti: null };
                if (this.$refs.buktiFile) this.$refs.buktiFile.value = '';
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            submitPermintaan() {
                if (this.items.length === 0) return;
                
                this.isLoading = true;
                
                const formData = new FormData();
                this.items.forEach((item, index) => {
                    formData.append(`items[${index}][barang_id]`, item.barang_id);
                    formData.append(`items[${index}][jumlah]`, item.jumlah);
                    if (item.alasan) formData.append(`items[${index}][alasan]`, item.alasan);
                    if (item.bukti) formData.append(`items[${index}][bukti]`, item.bukti);
                });

                fetch('{{ route("koordinator.permintaan.store") }}', {
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
                    if (res.status === 200 || res.status === 201) {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Berhasil!', 
                            text: res.body.message, 
                            confirmButtonColor: '#0F766E',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = res.body.redirect;
                        });
                    } else if (res.status === 400) {
                        Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup!', text: res.body.message });
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
