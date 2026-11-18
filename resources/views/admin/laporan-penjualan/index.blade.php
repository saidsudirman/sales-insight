@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('breadcrumb', 'Laporan Penjualan')

@section('content')

    <div class="mb-4">
        <h4 class="page-heading">Laporan Analitik Penjualan</h4>
        <p class="page-description">
            Lihat dan analisis data penjualan historis MIYA berdasarkan periode.
        </p>
    </div>

    {{-- Filter Periode --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">

            <h6 class="fw-bold mb-3">
                <i class="fa-solid fa-calendar-days me-2" style="color:#B09A3E"></i>
                Filter Periode Laporan
            </h6>

            <form action="{{ route('admin.laporan-penjualan.index') }}" method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">
                            Tanggal Mulai
                        </label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">
                            Tanggal Akhir
                        </label>
                        <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir }}">
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn text-white" style="background:#B09A3E">
                                <i class="fa-solid fa-filter me-1"></i>
                                Terapkan
                            </button>

                            <a href="{{ route('admin.laporan-penjualan.index') }}" class="btn btn-light border">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Reset
                            </a>

                            <a href="{{ route('admin.laporan-penjualan.export-excel', request()->query()) }}"
                                class="btn btn-success">
                                <i class="fas fa-file-excel me-1"></i>
                                Export Excel
                            </a>

                            <a href="{{ route('admin.laporan-penjualan.export-pdf', request()->query()) }}"
                                class="btn btn-danger">
                                <i class="fas fa-file-pdf me-1"></i>
                                Export PDF
                            </a>
                        </div>
                    </div>

                </div>

                @error('tanggal_akhir')
                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>
                @enderror

            </form>
        </div>
    </div>

    {{-- Ringkasan Laporan --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <small class="text-secondary">Total Pendapatan</small>
                    <h4 class="fw-bold mt-2 mb-0">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h4>
                    <small class="text-secondary">Akumulasi pendapatan</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <small class="text-secondary">Jumlah Transaksi</small>
                    <h4 class="fw-bold mt-2 mb-0">
                        {{ number_format($jumlahTransaksi, 0, ',', '.') }}
                    </h4>
                    <small class="text-secondary">Transaksi tercatat</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <small class="text-secondary">Produk Terjual</small>
                    <h4 class="fw-bold mt-2 mb-0">
                        {{ number_format($produkTerjual, 0, ',', '.') }}
                    </h4>
                    <small class="text-secondary">Total unit terjual</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <small class="text-secondary">Rata-rata Transaksi</small>
                    <h4 class="fw-bold mt-2 mb-0">
                        Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}
                    </h4>
                    <small class="text-secondary">Pendapatan per transaksi</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabel Laporan --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between
                            align-items-center gap-2 mb-3">

                <div>
                    <h6 class="fw-bold mb-1">Rincian Data Penjualan</h6>
                    <small class="text-secondary">
                        Data berdasarkan hasil import Excel.
                    </small>
                </div>

                {{-- Tombol ekspor ditambahkan pada tahap berikutnya --}}

            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Nomor Transaksi</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Ukuran</th>
                            <th>Warna</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($laporan as $index => $item)
                            <tr>
                                <td>
                                    {{ $laporan->firstItem() + $index }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $item->nomor_transaksi }}
                                </td>

                                <td>{{ $item->nama_produk }}</td>
                                <td>{{ $item->nama_kategori }}</td>
                                <td>{{ $item->nama_ukuran }}</td>
                                <td>{{ $item->nama_warna }}</td>

                                <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>

                                <td>
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td class="fw-semibold">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-secondary py-4">
                                    Belum ada data penjualan pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $laporan->links() }}
            </div>

        </div>
    </div>

@endsection