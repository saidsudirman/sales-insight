@extends('layouts.admin')

@section('title', 'Import Data Penjualan')
@section('page-title', 'Import Data Penjualan')

@section('content')
    <div class="container-fluid px-0">

        {{-- Header --}}
        <div class="mb-4">
            <h4 class="fw-bold mb-1">Import Data Penjualan</h4>
            <p class="text-muted mb-0">
                Masukkan data penjualan historis UMKM SAID melalui file Excel atau CSV.
            </p>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- Download Template --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 me-3" style="background: #e8f5e9;">
                                <i class="fas fa-file-excel fa-2x text-success"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">Template Excel</h5>
                                <small class="text-muted">Format data penjualan MIYA</small>
                            </div>
                        </div>

                        <p class="text-muted">
                            Unduh template terlebih dahulu. Isi data penjualan historis
                            sesuai kolom yang telah disediakan.
                        </p>

                        <div class="bg-light rounded-3 p-3 mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Format tanggal penjualan</span>
                            </div>

                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Informasi produk dan kategori</span>
                            </div>

                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Ukuran dan warna produk</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Harga, jumlah, dan diskon</span>
                            </div>
                        </div>

                        <a href="{{ route('admin.import-penjualan.template') }}" class="btn btn-success w-100">
                            <i class="fas fa-download me-2"></i>
                            Download Template Excel
                        </a>

                    </div>
                </div>
            </div>


            <hr class="my-4">

            <h6 class="fw-bold mb-3">
                <i class="fas fa-cloud-upload-alt me-2"></i>
                Upload Data Penjualan
            </h6>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.import-penjualan.upload') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label for="file" class="form-label fw-semibold">
                        Pilih File Excel / CSV
                    </label>

                    <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>

                    <small class="text-muted">
                        Format: XLSX, XLS, atau CSV. Maksimal 10 MB.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-upload me-2"></i>
                    Upload dan Preview Data
                </button>
            </form>


            {{-- Informasi Import --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-info-circle me-2" style="color: #C9B458;"></i>
                            Petunjuk Import Data
                        </h5>

                        <div class="d-flex mb-4">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3"
                                style="background: #C9B458; width: 35px; height: 35px; flex-shrink: 0;">
                                1
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Download template</h6>
                                <p class="text-muted mb-0">
                                    Unduh template Excel yang disediakan oleh sistem.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3"
                                style="background: #C9B458; width: 35px; height: 35px; flex-shrink: 0;">
                                2
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Isi data penjualan</h6>
                                <p class="text-muted mb-0">
                                    Masukkan data historis penjualan pakaian sesuai
                                    format kolom pada template.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3"
                                style="background: #C9B458; width: 35px; height: 35px; flex-shrink: 0;">
                                3
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Upload file</h6>
                                <p class="text-muted mb-0">
                                    Upload file Excel atau CSV untuk diperiksa
                                    dan diproses oleh sistem.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3"
                                style="background: #C9B458; width: 35px; height: 35px; flex-shrink: 0;">
                                4
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Lihat hasil analisis</h6>
                                <p class="text-muted mb-0">
                                    Data yang berhasil diimpor akan digunakan
                                    untuk menghasilkan informasi analitik penjualan.
                                </p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Gunakan data penjualan historis. Halaman ini bukan
                            untuk melakukan transaksi penjualan secara langsung.
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection