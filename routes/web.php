<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('koordinator', \App\Http\Controllers\Admin\KoordinatorController::class)->except(['create', 'edit', 'show']);
    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class)->except(['create', 'edit', 'show']);
    Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class)->except(['create', 'edit', 'show']);
    Route::resource('satuan', \App\Http\Controllers\Admin\SatuanController::class)->except(['create', 'edit', 'show']);
    
    // Manajemen Stok
    Route::resource('stok-masuk', \App\Http\Controllers\Admin\StokMasukController::class)->except(['create', 'edit', 'show']);
    Route::resource('stok-keluar', \App\Http\Controllers\Admin\StokKeluarController::class)->except(['create', 'edit', 'show']);
    Route::get('riwayat-stok', [\App\Http\Controllers\Admin\RiwayatStokController::class, 'index'])->name('riwayat-stok.index');
    // Permintaan & Validasi
    Route::get('validasi-permintaan', [\App\Http\Controllers\Admin\ValidasiPermintaanController::class, 'index'])->name('validasi-permintaan.index');
    Route::post('validasi-permintaan/{id}', [\App\Http\Controllers\Admin\ValidasiPermintaanController::class, 'proses'])->name('validasi-permintaan.proses');
    
    // Distribusi
    Route::resource('distribusi', \App\Http\Controllers\Admin\DistribusiController::class)->only(['index', 'store']);
    
    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('stok', [\App\Http\Controllers\Admin\LaporanController::class, 'stok'])->name('stok');
        Route::get('stok/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportStokPdf'])->name('stok.pdf');
        Route::get('stok/excel', [\App\Http\Controllers\Admin\LaporanController::class, 'exportStokExcel'])->name('stok.excel');
        
        Route::get('permintaan', [\App\Http\Controllers\Admin\LaporanController::class, 'permintaan'])->name('permintaan');
        Route::get('permintaan/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportPermintaanPdf'])->name('permintaan.pdf');
        Route::get('permintaan/excel', [\App\Http\Controllers\Admin\LaporanController::class, 'exportPermintaanExcel'])->name('permintaan.excel');
    });
});

// Koordinator Routes
Route::middleware(['auth', 'koordinator'])->prefix('koordinator')->name('koordinator.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Koordinator\DashboardController::class, 'index'])->name('dashboard');
    
    // Barang (Katalog)
    Route::get('barang', [\App\Http\Controllers\Koordinator\BarangController::class, 'index'])->name('barang.index');
    
    // Bukti Kondisi / Terima Barang
    Route::get('bukti-terima', [\App\Http\Controllers\Koordinator\BuktiKondisiController::class, 'index'])->name('bukti-terima.index');
    Route::post('bukti-terima/{id}', [\App\Http\Controllers\Koordinator\BuktiKondisiController::class, 'store'])->name('bukti-terima.store');
    
    // Permintaan
    Route::resource('permintaan', \App\Http\Controllers\Koordinator\PermintaanController::class)->only(['index', 'create', 'store', 'destroy']);
    
    // Profil Saya
    Route::get('profile', [\App\Http\Controllers\Koordinator\ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [\App\Http\Controllers\Koordinator\ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/password', [\App\Http\Controllers\Koordinator\ProfileController::class, 'updatePassword'])->name('profile.password');
});