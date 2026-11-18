
@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

    <div class="mb-4">
        <h4 class="page-heading">Selamat Datang, {{ auth()->user()->name }}!</h4>
        <p class="page-description">
            Berikut adalah ringkasan aktivitas penjualan MIYA.
        </p>
    </div>

    <div class="row g-3">

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Total Produk</small>
                            <h3 class="fw-bold mt-2 mb-0">0</h3>
                        </div>
                        <div class="rounded-3 p-3"
                             style="background:#F3EBD0;color:#B09A3E">
                            <i class="fa-solid fa-box fs-5"></i>
                        </div>
                    </div>
                    <small class="text-secondary">Produk terdaftar</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Total Kategori</small>
                            <h3 class="fw-bold mt-2 mb-0">0</h3>
                        </div>
                        <div class="rounded-3 p-3 bg-primary-subtle text-primary">
                            <i class="fa-solid fa-tags fs-5"></i>
                        </div>
                    </div>
                    <small class="text-secondary">Kategori tersedia</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Total Pelanggan</small>
                            <h3 class="fw-bold mt-2 mb-0">0</h3>
                        </div>
                        <div class="rounded-3 p-3 bg-success-subtle text-success">
                            <i class="fa-solid fa-users fs-5"></i>
                        </div>
                    </div>
                    <small class="text-secondary">Pelanggan terdaftar</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Total Transaksi</small>
                            <h3 class="fw-bold mt-2 mb-0">0</h3>
                        </div>
                        <div class="rounded-3 p-3 bg-warning-subtle text-warning">
                            <i class="fa-solid fa-cart-shopping fs-5"></i>
                        </div>
                    </div>
                    <small class="text-secondary">Transaksi tercatat</small>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-3 mt-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-2">Aktivitas Penjualan</h6>
            <p class="text-secondary small mb-0">
                Grafik penjualan akan ditampilkan di bagian ini.
            </p>
        </div>
    </div>

@endsection
