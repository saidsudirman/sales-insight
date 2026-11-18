
@extends('layouts.admin')

@section('title', 'Preview Data Penjualan')
@section('page-title', 'Preview Data Penjualan')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Preview Data Penjualan</h4>
            <p class="text-muted mb-0">
                Periksa data sebelum melanjutkan proses import.
            </p>
        </div>

        <a href="{{ route('admin.import-penjualan.index') }}"
           class="btn btn-light border">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted">Total baris data terbaca</small>
                    <h3 class="fw-bold mb-0">{{ $totalRows }}</h3>
                </div>

                <div class="rounded-3 p-3 bg-light">
                    <i class="fas fa-file-excel fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

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

            <small>
                Silakan gunakan template MIYA Sales Insight.
            </small>
        </div>
    @elseif($totalRows > 0)
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            Struktur kolom berhasil dikenali. Periksa kembali isi datanya.
        </div>
    @else
        <div class="alert alert-warning">
            File belum memiliki baris data.
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Data dari File Excel</h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>

                            @if(!empty($rows))
                                @foreach(array_keys($rows[0]) as $header)
                                    <th>{{ ucwords(str_replace('_', ' ', $header)) }}</th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse(array_slice($rows, 0, 20) as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                @foreach($row as $value)
                                    <td>
                                        {{ is_scalar($value) ? $value : '' }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
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
                Menampilkan 20 dari {{ $totalRows }} baris data.
            </div>
        @endif
    </div>

    <div class="alert alert-info mt-4 mb-0">
        <i class="fas fa-info-circle me-2"></i>
        Data ini masih berupa preview. Belum ada data yang disimpan
        ke database penjualan.
    </div>

</div>
@endsection
