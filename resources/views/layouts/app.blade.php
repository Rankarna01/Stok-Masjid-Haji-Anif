<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Stok Kebersihan Mesjid' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0F766E', 
                        secondary: '#14B8A6',
                        success: '#10B981', 
                        warning: '#F59E0B', 
                        danger: '#EF4444', 
                        info: '#3B82F6',    
                        purple: '#8B5CF6',  
                        background: '#F8FAFC',
                        card: '#FFFFFF',
                        border: '#E2E8F0',
                        text: '#334155',
                        textDark: '#0F172A',
                    }
                }
            }
        }
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- LottieFiles Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F8FAFC; color: #334155; }
        .card-shadow { box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); }
        .transition-smooth { transition: all 0.2s ease-in-out; }
        
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 4px; }
        .sidebar-scroll:hover::-webkit-scrollbar-thumb { background: #CBD5E1; }
    </style>
</head>
<body class="bg-background antialiased min-h-screen w-full" x-data="{ sidebarOpen: false, profileDropdownOpen: false }">

    @auth
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Backdrop (Mobile) -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false" style="display: none;"></div>

        <!-- Sidebar -->
        <aside x-show="sidebarOpen || window.innerWidth >= 1024" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-30 w-72 bg-white border-r border-border flex flex-col lg:relative lg:translate-x-0" @click.outside="if(window.innerWidth < 1024) sidebarOpen = false">
            
            <!-- Logo Section -->
            <div class="flex items-center h-20 px-6 border-b border-border mb-4">
                <a href="#" class="flex items-center gap-3 w-full">
                    @if(isset($appSetting) && $appSetting->logo)
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm border border-gray-100 overflow-hidden shrink-0">
                            <img src="{{ Storage::url($appSetting->logo) }}" alt="Logo" class="w-full h-full object-contain p-1">
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center font-bold text-xl shadow-sm shrink-0">
                            {{ isset($appSetting) && $appSetting->nama_sistem ? substr($appSetting->nama_sistem, 0, 1) : 'S' }}
                        </div>
                    @endif
                    <div class="leading-tight overflow-hidden">
                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider block truncate">{{ $appSetting->nama_yayasan ?? 'Stok Masjid' }}</span>
                        <span class="text-sm font-bold text-primary uppercase block truncate">{{ $appSetting->nama_sistem ?? 'Sistem Inventaris' }}</span>
                    </div>
                </a>
            </div>

            @if(Auth::user()->role === 'koordinator')
            <!-- Koordinator Profile Block -->
            <div class="px-6 py-4 border-b border-border mb-2 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E0F2FE&color=0284C7" class="rounded-full" alt="Avatar">
                </div>
                <div>
                    <p class="text-sm font-semibold text-textDark">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-gray-500">{{ Auth::user()->nama_mesjid }}</p>
                    <div class="flex items-center gap-1 mt-0.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-success"></div>
                        <span class="text-[10px] text-gray-500">Online</span>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="overflow-y-auto sidebar-scroll flex-grow px-4 pb-4">
                <ul class="flex flex-col space-y-1">
                    
                    @if(Auth::user()->role === 'admin')
                        <!-- Admin Sidebar -->
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <span class="font-medium text-sm">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="px-2 mt-6 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Master Data</li>
                        <!-- Dropdown Data Barang -->
                        <li x-data="{ open: {{ request()->routeIs('admin.barang.*', 'admin.kategori.*', 'admin.satuan.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 {{ request()->routeIs('admin.barang.*', 'admin.kategori.*', 'admin.satuan.*') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 {{ request()->routeIs('admin.barang.*', 'admin.kategori.*', 'admin.satuan.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <span class="font-medium text-sm">Katalog Barang</span>
                                </div>
                                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <!-- Submenu -->
                            <ul x-show="open" class="mt-2 space-y-1 px-2" x-transition.opacity>
                                <li>
                                    <a href="{{ route('admin.barang.index') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('admin.barang.*') ? 'text-primary font-bold bg-primary/5' : 'text-gray-500 hover:text-primary hover:bg-gray-50' }} rounded-lg transition-smooth text-sm">
                                        <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.barang.*') ? 'bg-primary' : 'bg-gray-300' }}"></div>
                                        Daftar Barang
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.kategori.index') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('admin.kategori.*') ? 'text-primary font-bold bg-primary/5' : 'text-gray-500 hover:text-primary hover:bg-gray-50' }} rounded-lg transition-smooth text-sm">
                                        <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.kategori.*') ? 'bg-primary' : 'bg-gray-300' }}"></div>
                                        Kategori
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.satuan.index') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('admin.satuan.*') ? 'text-primary font-bold bg-primary/5' : 'text-gray-500 hover:text-primary hover:bg-gray-50' }} rounded-lg transition-smooth text-sm">
                                        <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.satuan.*') ? 'bg-primary' : 'bg-gray-300' }}"></div>
                                        Satuan
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="{{ route('admin.koordinator.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.koordinator.*') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.koordinator.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="font-medium text-sm">Data Koordinator</span>
                        </a></li>
                        
                        <li class="px-2 mt-6 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Manajemen Stok</li>
                        <li><a href="{{ route('admin.stok-masuk.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.stok-masuk.*') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.stok-masuk.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <span class="font-medium text-sm">Barang Masuk</span>
                        </a></li>
                        <li><a href="{{ route('admin.stok-keluar.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.stok-keluar.*') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.stok-keluar.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span class="font-medium text-sm">Barang Keluar</span>
                        </a></li>
                        <li><a href="{{ route('admin.riwayat-stok.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.riwayat-stok.*') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.riwayat-stok.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium text-sm">Riwayat Stok</span>
                        </a></li>
                        
                        <li class="px-2 mt-6 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Permintaan</li>
                        <li><a href="{{ route('admin.validasi-permintaan.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.validasi-permintaan.*') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.validasi-permintaan.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium text-sm">Validasi Permintaan</span>
                        </a></li>
                        
                        <li class="px-2 mt-6 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Distribusi</li>
                        <li><a href="{{ route('admin.distribusi.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.distribusi.*') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.distribusi.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            <span class="font-medium text-sm">Distribusi Barang</span>
                        </a></li>

                        <li class="px-2 mt-6 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Laporan</li>
                        <li><a href="{{ route('admin.laporan.stok') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.laporan.stok') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.laporan.stok') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="font-medium text-sm">Laporan Stok</span>
                        </a></li>
                        <li><a href="{{ route('admin.laporan.permintaan') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.laporan.permintaan') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.laporan.permintaan') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="font-medium text-sm">Laporan Permintaan</span>
                        </a></li>
                        <li><a href="{{ route('admin.laporan.distribusi') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.laporan.distribusi') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.laporan.distribusi') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="font-medium text-sm">Laporan Distribusi</span>
                        </a></li>

                        <li class="px-2 mt-6 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</li>
                        <li><a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('admin.settings.*') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.settings.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-medium text-sm">Pengaturan</span>
                        </a></li>
                    @else
                        <!-- Koordinator Sidebar -->
                        <li>
                            <a href="{{ route('koordinator.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('koordinator.dashboard') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                                <svg class="w-5 h-5 {{ request()->routeIs('koordinator.dashboard') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <span class="font-medium text-sm">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="mt-2"><a href="{{ route('koordinator.barang.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('koordinator.barang.index') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('koordinator.barang.index') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            <span class="font-medium text-sm">Daftar Barang</span>
                        </a></li>
                        
                        <li><a href="{{ route('koordinator.permintaan.create') }}" class="flex items-center justify-between px-4 py-2.5 {{ request()->routeIs('koordinator.permintaan.create') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ request()->routeIs('koordinator.permintaan.create') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="font-medium text-sm">Buat Permintaan</span>
                            </div>
                        </a></li>
                        
                        <li><a href="{{ route('koordinator.permintaan.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('koordinator.permintaan.index') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('koordinator.permintaan.index') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium text-sm">Riwayat Permintaan</span>
                        </a></li>
                        
                        <li><a href="{{ route('koordinator.bukti-terima.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('koordinator.bukti-terima.index') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('koordinator.bukti-terima.index') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-medium text-sm">Bukti Kondisi Barang</span>
                        </a></li>

                        <li class="px-2 mt-6 mb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</li>
                        <li><a href="{{ route('koordinator.profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 {{ request()->routeIs('koordinator.profile.*') ? 'bg-primary text-white shadow-sm' : 'text-text hover:bg-gray-50' }} rounded-xl transition-smooth">
                            <svg class="w-5 h-5 {{ request()->routeIs('koordinator.profile.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="font-medium text-sm">Profil Saya</span>
                        </a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-danger hover:bg-danger/10 rounded-xl transition-smooth mt-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    <span class="font-medium text-sm">Logout</span>
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Bottom Card Widget -->
            <div class="p-4 mt-auto">
                <div class="bg-primary rounded-xl p-4 text-center relative overflow-hidden text-white shadow-md">
                    <!-- Decorative background element -->
                    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M30 0L30 60 M0 30L60 30\' stroke=\'%23ffffff\' stroke-width=\'2\' fill=\'none\'/%3E%3C/svg%3E');"></div>
                    
                    <div class="w-16 h-16 mx-auto relative z-10">
                        <lottie-player 
                            src="{{ asset('d788a264-1188-11ee-870a-d761e719f38a.json') }}" 
                            background="transparent" 
                            speed="1" 
                            style="width: 100%; height: 100%;" 
                            loop 
                            autoplay>
                        </lottie-player>
                    </div>
                    <p class="text-[11px] font-semibold mb-1 relative z-10 mt-1">Permintaan & Stok</p>
                    <p class="text-[10px] opacity-80 mb-2 relative z-10 leading-tight">Barang Kebersihan Mesjid</p>
                    <p class="text-[9px] opacity-60 relative z-10">&copy; 2024 Yayasan Haji Anif</p>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Header -->
            <header class="flex items-center justify-between px-8 h-20 bg-transparent shrink-0">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-text focus:outline-none lg:hidden mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-2xl font-semibold text-textDark">{{ $title ?? 'Dashboard' }}</h1>
                </div>

                <div class="flex items-center gap-5">    
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button @click="profileDropdownOpen = !profileDropdownOpen" @click.away="profileDropdownOpen = false" class="flex items-center gap-3 focus:outline-none bg-white py-1.5 px-3 rounded-full border border-gray-200 shadow-sm hover:shadow transition-smooth">
                            <div class="flex flex-col items-end hidden md:block text-right">
                                <span class="text-sm font-semibold text-textDark">{{ Auth::user()->name }}</span>
                                <span class="text-[11px] text-gray-500 capitalize">{{ Auth::user()->role }}</span>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0F766E&color=fff" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm" alt="Profile">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="profileDropdownOpen" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-card rounded-xl card-shadow py-2 border border-border z-20" style="display: none;">
                            <a href="#" class="block px-4 py-2 text-sm text-text hover:bg-gray-50 transition-smooth">Profil Saya</a>
                            <a href="#" class="block px-4 py-2 text-sm text-text hover:bg-gray-50 transition-smooth">Pengaturan</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-danger hover:bg-danger/5 transition-smooth">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto px-8 pb-8">
                @yield('content')
            </main>
            
        </div>
    </div>
    @else
        <!-- Guest Layout (Login) -->
        <div class="min-h-screen w-full flex flex-col justify-center items-center bg-background p-4 sm:p-6 lg:p-8">
            @yield('content')
        </div>
    @endauth

    @stack('scripts')
</body>
</html>
