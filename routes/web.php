<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LaporanPenjualanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\UkuranController;
use App\Http\Controllers\Admin\VarianProdukController;
use App\Http\Controllers\Admin\WarnaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportPenjualanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::user()->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        }
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');
            Route::patch('/kategoris/{kategori}/toggle-status', [
                KategoriController::class,
                'toggleStatus'
            ])->name('kategoris.toggle-status');

            Route::resource('kategoris', KategoriController::class)
                ->except(['show']);
            Route::patch('/ukurans/{ukuran}/toggle-status', [
                UkuranController::class,
                'toggleStatus'
            ])->name('ukurans.toggle-status');

            Route::resource('ukurans', UkuranController::class)
                ->except(['show']);

            Route::patch('/warnas/{warna}/toggle-status', [
                WarnaController::class,
                'toggleStatus'
            ])->name('warnas.toggle-status');

            Route::resource('warnas', WarnaController::class)
                ->except(['show']);
            Route::patch('/produks/{produk}/toggle-status', [
                ProdukController::class,
                'toggleStatus'

            ])->name('produks.toggle-status');

            Route::resource('produks', ProdukController::class)
                ->except(['show']);
            Route::patch('/varian-produks/{varianProduk}/toggle-status', [VarianProdukController::class, 'toggleStatus'])
                ->name('varian-produks.toggle-status');

            Route::resource('varian-produks', VarianProdukController::class)
                ->except(['show']);
            Route::resource('pelanggans', PelangganController::class)
                ->except(['show']);
            Route::get('/import-penjualan', [ImportPenjualanController::class, 'index'])
                ->name('import-penjualan.index');

            Route::get('/import-penjualan/template', [ImportPenjualanController::class, 'downloadTemplate'])
                ->name('import-penjualan.template');

            Route::post('/import-penjualan/upload', [ImportPenjualanController::class, 'upload'])
                ->name('import-penjualan.upload');

            Route::get('/import-penjualan/preview', [ImportPenjualanController::class, 'preview'])
                ->name('import-penjualan.preview');
            Route::post('/import-penjualan/prepare-master', [ImportPenjualanController::class, 'prepareMasterData'])
                ->name('import-penjualan.prepare-master');
            Route::post('/import-penjualan/confirm', [ImportPenjualanController::class, 'confirmImport'])
                ->name('import-penjualan.confirm');
            Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index'])
                ->name('laporan-penjualan.index');
            Route::get('/laporan-penjualan/export-excel', [
                LaporanPenjualanController::class,
                'exportExcel'
            ])->name('laporan-penjualan.export-excel');
            Route::get('/laporan-penjualan/export-pdf', [
    LaporanPenjualanController::class,
    'exportPdf'
])->name('laporan-penjualan.export-pdf');
        });

    Route::prefix('pemilik')
        ->name('pemilik.')
        ->middleware('role:pemilik')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('pemilik.dashboard');
            })->name('dashboard');
        });
});