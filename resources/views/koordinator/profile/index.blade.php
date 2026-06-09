@extends('layouts.app', ['title' => 'Profil Saya'])

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ tab: 'profil' }">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-textDark">Pengaturan Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi profil, keamanan, dan bantuan.</p>
    </div>

    <!-- Tabs -->
    <div class="flex space-x-1 bg-white p-1 rounded-xl border border-gray-200 mb-6 w-max shadow-sm">
        <button @click="tab = 'profil'" :class="{'bg-primary text-white shadow': tab === 'profil', 'text-gray-500 hover:text-textDark hover:bg-gray-50': tab !== 'profil'}" class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-smooth flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Profil Saya
        </button>
        <button @click="tab = 'keamanan'" :class="{'bg-primary text-white shadow': tab === 'keamanan', 'text-gray-500 hover:text-textDark hover:bg-gray-50': tab !== 'keamanan'}" class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-smooth flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            Keamanan
        </button>
        <button @click="tab = 'bantuan'" :class="{'bg-primary text-white shadow': tab === 'bantuan', 'text-gray-500 hover:text-textDark hover:bg-gray-50': tab !== 'bantuan'}" class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-smooth flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Bantuan
        </button>
    </div>

    <!-- Alert Success Profil -->
    @if(session('success'))
    <div class="bg-success/10 text-success px-4 py-3 rounded-xl border border-success/20 mb-6 flex items-center gap-2 font-medium text-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Alert Success Password -->
    @if(session('success_password'))
    <div class="bg-success/10 text-success px-4 py-3 rounded-xl border border-success/20 mb-6 flex items-center gap-2 font-medium text-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('success_password') }}
    </div>
    @endif

    <!-- Alert Error -->
    @if($errors->any())
    <div class="bg-danger/10 text-danger px-4 py-3 rounded-xl border border-danger/20 mb-6 text-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tab 1: Profil -->
    <div x-show="tab === 'profil'" class="bg-card rounded-2xl p-6 md:p-8 card-shadow border border-border">
        <form action="{{ route('koordinator.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="flex items-center gap-6 mb-8 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                <div class="shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E0F2FE&color=0284C7&size=100" alt="Avatar" class="w-20 h-20 rounded-2xl shadow-sm border border-white">
                </div>
                <div>
                    <h3 class="text-xl font-bold text-textDark">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Mesjid / Wilayah Koordinasi</label>
                    <input type="text" name="nama_mesjid" value="{{ old('nama_mesjid', Auth::user()->nama_mesjid) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor HP / WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp) }}" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm" placeholder="Contoh: 081234567890">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-smooth shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- Tab 2: Keamanan -->
    <div x-show="tab === 'keamanan'" class="bg-card rounded-2xl p-6 md:p-8 card-shadow border border-border" style="display: none;">
        <h3 class="text-lg font-bold text-textDark mb-1">Ubah Password</h3>
        <p class="text-sm text-gray-500 mb-6">Pastikan akun Anda menggunakan password yang kuat dan unik.</p>

        <form action="{{ route('koordinator.profile.password') }}" method="POST" class="space-y-6 max-w-md">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Saat Ini</label>
                <input type="password" name="current_password" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Baru</label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
                <p class="text-[10px] text-gray-400 mt-1">Minimal 8 karakter.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-smooth text-sm">
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-smooth shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

    <!-- Tab 3: Bantuan -->
    <div x-show="tab === 'bantuan'" class="bg-card rounded-2xl p-6 md:p-8 card-shadow border border-border" style="display: none;">
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-textDark mb-2">Pusat Bantuan Yayasan</h3>
            <p class="text-gray-500 text-sm max-w-md mx-auto mb-8">Jika Anda mengalami kendala teknis atau memiliki pertanyaan seputar operasional sistem dan distribusi barang, silakan hubungi tim Admin.</p>
            
            <div class="inline-flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/6281234567890" target="_blank" class="px-6 py-3 bg-green-500 text-white font-semibold rounded-xl hover:bg-green-600 transition-smooth shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Hubungi via WhatsApp
                </a>
                <a href="mailto:admin@yayasan.com" class="px-6 py-3 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-smooth shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Kirim Email
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
