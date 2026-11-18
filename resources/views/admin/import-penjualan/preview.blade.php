@extends('layouts.admin')

@section('title', 'Preview Data Penjualan')
@section('page-title', 'Preview Data Penjualan')

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif
    <div class="container-fluid px-0">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Preview Data Penjualan</h4>
                <p class="text-muted mb-0">
                    Periksa hasil validasi sebelum melanjutkan proses import.
                </p>
            </div>

            <a href="{{ route('admin.import-penjualan.index') }}" class="btn btn-light border">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Ringkasan --}}
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Total Baris Data</small>
                        <h3 class="fw-bold mb-0">{{ $totalRows }}</h3>
                        <small class="text-muted">Data terbaca dari Excel</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Data Valid</small>
                        <h3 class="fw-bold text-success mb-0">{{ $totalValid }}</h3>
                        <small class="text-muted">Lolos pemeriksaan</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Data Bermasalah</small>
                        <h3 class="fw-bold text-danger mb-0">{{ $totalInvalid }}</h3>
                        <small class="text-muted">Perlu diperbaiki</small>
                    </div>
                </div>
            </div>

        </div>

        {{-- Informasi format kolom --}}
        @if(count($missingHeaders) > 0)

            <div class="alert alert-danger">
                <h6 class="fw-bold">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Format kolom belum sesuai
                </h6>

                <p class="mb-1">Kolom berikut tidak ditemukan:</p>

                <ul class="mb-0">
                    @foreach($missingHeaders as $header)
                        <li>{{ $header }}</li>
                    @endforeach
                </ul>

                <small>Silakan gunakan template MIYA Sales Insight.</small>
            </div>

        @elseif($totalRows > 0 && $totalInvalid === 0)

            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                Seluruh data berhasil melewati pemeriksaan.
            </div>

        @elseif($totalInvalid > 0)

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Ditemukan {{ $totalInvalid }} baris data yang perlu diperbaiki.
                Periksa keterangan pada kolom hasil validasi.
            </div>

        @else

            <div class="alert alert-warning">
                File belum memiliki baris data.
            </div>

        @endif


        {{-- Tombol persiapan master data --}}
        @if(count($missingHeaders) === 0 && $totalRows > 0)

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                    <div>
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-database text-primary me-2"></i>
                            Persiapan Master Data
                        </h6>

                        <p class="text-muted small mb-0">
                            Sistem akan membuat kategori, ukuran, warna, produk,
                            dan varian yang belum tersedia berdasarkan file Excel.
                        </p>
                    </div>

                    <form action="{{ route('admin.import-penjualan.prepare-master') }}" method="POST"
                        onsubmit="return confirm('Siapkan master data berdasarkan file Excel ini? Data master yang sudah tersedia tidak akan dibuat ulang.');">

                        @csrf

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-magic me-2"></i>
                            Siapkan Master Data
                        </button>

                    </form>

                </div>
            </div>

        @endif


        {{-- Tabel preview --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Hasil Pemeriksaan Data Excel</h6>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0 align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>No.</th>

                                @if(!empty($validatedRows))
                                    @foreach(array_keys($validatedRows[0]['data']) as $header)
                                        <th>
                                            {{ ucwords(str_replace('_', ' ', $header)) }}
                                        </th>
                                    @endforeach
                                @endif

                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($validatedRows as $item)

                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    @foreach($item['data'] as $key => $value)
                                        <td>
                                            @if($key === 'harga_jual' || $key === 'diskon_transaksi')
                                                {{ is_numeric($value) ? number_format((float) $value, 0, ',', '.') : $value }}
                                            @else
                                                {{ is_scalar($value) ? $value : '' }}
                                            @endif
                                        </td>
                                    @endforeach

                                    <td>
                                        @if($item['is_valid'])
                                            <span class="badge bg-success">
                                                Valid
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Bermasalah
                                            </span>
                                        @endif
                                    </td>

                                    <td style="min-width: 250px;">
                                        @if($item['is_valid'])
                                            <span class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Data sesuai
                                            </span>
                                        @else
                                            <ul class="text-danger mb-0 ps-3">
                                                @foreach($item['errors'] as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="12" class="text-center py-4 text-muted">
                                        Belum ada data untuk ditampilkan.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>
            </div>

            @if($totalRows > 20)
                <div class="card-footer bg-white text-muted">
                    Total {{ $totalRows }} baris data berhasil dibaca.
                </div>
            @endif

        </div>

        <div class="alert alert-info mt-4 mb-0">
            <i class="fas fa-info-circle me-2"></i>
            Pemeriksaan ini belum menyimpan data ke database.
            Data hanya akan diimpor setelah melewati tahap konfirmasi.
        </div>

    </div>
@endsection