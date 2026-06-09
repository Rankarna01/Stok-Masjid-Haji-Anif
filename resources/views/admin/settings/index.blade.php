@extends('layouts.app', ['title' => 'Pengaturan Sistem'])

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-card rounded-2xl p-6 md:p-8 card-shadow border border-border">
        <div class="mb-8 border-b border-gray-100 pb-5">
            <h2 class="text-2xl font-bold text-textDark">Pengaturan Profil & Sistem</h2>
            <p class="text-sm text-gray-500 mt-1">Sesuaikan nama sistem, logo, dan profil organisasi (akan digunakan untuk KOP Surat Laporan).</p>
        </div>

        @if(session('success'))
        <div class="bg-success/10 text-success px-4 py-3 rounded-xl border border-success/20 mb-6 flex items-center gap-2 font-medium text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-danger/10 text-danger px-4 py-3 rounded-xl border border-danger/20 mb-6 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Logo Section -->
            <div class="flex flex-col md:flex-row gap-6 items-start bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                <div class="shrink-0">
                    <div class="w-24 h-24 rounded-2xl bg-white border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden shadow-sm">
                        @if($setting->logo)
                            <img src="{{ Storage::url($setting->logo) }}" alt="Logo" class="w-full h-full object-contain p-2">
                        @else
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-bold text-textDark mb-1">Logo Aplikasi / Instansi</label>
                    <p class="text-xs text-gray-500 mb-3 leading-relaxed">Logo ini akan ditampilkan pada sidebar, halaman login, dan pojok KOP Surat Laporan PDF. Rekomendasi format PNG transparan.</p>
                    <input type="file" name="logo" accept="image/png, image/jpeg" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-smooth">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Sistem -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Sistem</label>
                    <input type="text" name="nama_sistem" value="{{ old('nama_sistem', $setting->nama_sistem) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                </div>

                <!-- Nama Yayasan -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Organisasi / Yayasan</label>
                    <input type="text" name="nama_yayasan" value="{{ old('nama_yayasan', $setting->nama_yayasan) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                </div>

                <!-- Telepon -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $setting->telepon) }}" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $setting->email) }}" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">{{ old('alamat', $setting->alamat) }}</textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-smooth shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
