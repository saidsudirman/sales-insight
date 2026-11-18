@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

    <div class="mb-4">
        <h4 class="page-heading">Selamat Datang, {{ auth()->user()->name }}!</h4>
        <p class="page-description">
            Berikut adalah ringkasan analitik penjualan MIYA.
        </p>
    </div>

    {{-- Filter Periode Penjualan --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">

            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-calendar-days" style="color:#B09A3E"></i>
                <h6 class="fw-bold mb-0">Filter Periode Penjualan</h6>
            </div>

            <form action="{{ route('admin.dashboard') }}" method="GET">

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

                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light border">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Reset
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

    <div class="row g-3">

        {{-- Total Pendapatan --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Total Pendapatan</small>
                            <h3 class="fw-bold mt-2 mb-0">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="rounded-3 p-3" style="background:#F3EBD0;color:#B09A3E">
                            <i class="fa-solid fa-money-bill-wave fs-5"></i>
                        </div>
                    </div>
                    <small class="text-secondary">Akumulasi pendapatan</small>
                </div>
            </div>
        </div>

        {{-- Jumlah Transaksi --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Jumlah Transaksi</small>
                            <h3 class="fw-bold mt-2 mb-0">
                                {{ number_format($jumlahTransaksi, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="rounded-3 p-3 bg-primary-subtle text-primary">
                            <i class="fa-solid fa-receipt fs-5"></i>
                        </div>
                    </div>
                    <small class="text-secondary">Transaksi tercatat</small>
                </div>
            </div>
        </div>

        {{-- Produk Terjual --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Produk Terjual</small>
                            <h3 class="fw-bold mt-2 mb-0">
                                {{ number_format($produkTerjual, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="rounded-3 p-3 bg-success-subtle text-success">
                            <i class="fa-solid fa-box-open fs-5"></i>
                        </div>
                    </div>
                    <small class="text-secondary">Total unit terjual</small>
                </div>
            </div>
        </div>

        {{-- Rata-rata Transaksi --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Rata-rata Transaksi</small>
                            <h3 class="fw-bold mt-2 mb-0">
                                Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="rounded-3 p-3 bg-warning-subtle text-warning">
                            <i class="fa-solid fa-chart-line fs-5"></i>
                        </div>
                    </div>
                    <small class="text-secondary">Pendapatan per transaksi</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Informasi Master Data --}}
    <div class="row g-3 mt-2">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-secondary">Total Produk Terdaftar</small>
                            <h4 class="fw-bold mt-2 mb-0">
                                {{ number_format($totalProduk, 0, ',', '.') }}
                            </h4>
                        </div>
                        <i class="fa-solid fa-box fs-3" style="color:#B09A3E"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <small class="text-secondary">Total Kategori</small>
                    <h4 class="fw-bold mt-2 mb-0">
                        {{ number_format($totalKategori, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <small class="text-secondary">Total Pelanggan</small>
                    <h4 class="fw-bold mt-2 mb-0">
                        {{ number_format($totalPelanggan, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

    </div>


    {{-- Grafik Tren Penjualan --}}
    <div class="card border-0 shadow-sm rounded-3 mt-4">
        <div class="card-body p-4">
            <div class="mb-4">
                <h6 class="fw-bold mb-1">Tren Penjualan</h6>
                <p class="text-secondary small mb-0">
                    Perkembangan pendapatan berdasarkan tanggal transaksi.
                </p>
            </div>

            <div style="height: 320px;">
                <canvas id="grafikPenjualan"></canvas>
            </div>
        </div>
    </div>

    {{-- Produk Terlaris --}}
    <div class="card border-0 shadow-sm rounded-3 mt-4">
        <div class="card-body p-4">

            <div class="mb-4">
                <h6 class="fw-bold mb-1">5 Produk Terlaris</h6>
                <p class="text-secondary small mb-0">
                    Produk berdasarkan jumlah unit yang terjual.
                </p>
            </div>

            @forelse ($produkTerlaris as $index => $produk)
                <div class="d-flex align-items-center justify-content-between
                                                py-3 {{ !$loop->last ? 'border-bottom' : '' }}">

                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center
                                                        justify-content-center fw-bold" style="width:40px;height:40px;
                                                        background:#F3EBD0;color:#B09A3E">
                            {{ $index + 1 }}
                        </div>

                        <div>
                            <div class="fw-semibold">
                                {{ $produk->nama_produk }}
                            </div>
                            <small class="text-secondary">
                                Pendapatan: Rp
                                {{ number_format($produk->total_pendapatan, 0, ',', '.') }}
                            </small>
                        </div>
                    </div>

                    <div class="text-end">
                        <span class="fw-bold">
                            {{ number_format($produk->total_terjual, 0, ',', '.') }}
                        </span>
                        <div>
                            <small class="text-secondary">Unit terjual</small>
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-4 text-secondary">
                    Belum ada data penjualan.
                </div>
            @endforelse

        </div>
    </div>

    {{-- Analisis Kategori, Ukuran, dan Warna --}}
    <div class="row g-3 mt-2">

        {{-- Kategori --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-1">Penjualan per Kategori</h6>
                    <p class="text-secondary small mb-3">
                        Kategori berdasarkan unit terjual.
                    </p>

                    @forelse ($penjualanKategori as $kategori)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>{{ $kategori->nama_kategori }}</span>
                            <span class="fw-bold">
                                {{ number_format($kategori->total_terjual, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-secondary text-center py-3 mb-0">
                            Belum ada data.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Ukuran --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-1">Penjualan per Ukuran</h6>
                    <p class="text-secondary small mb-3">
                        Ukuran berdasarkan unit terjual.
                    </p>

                    @forelse ($penjualanUkuran as $ukuran)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>{{ $ukuran->nama_ukuran }}</span>
                            <span class="fw-bold">
                                {{ number_format($ukuran->total_terjual, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-secondary text-center py-3 mb-0">
                            Belum ada data.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Warna --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-1">Penjualan per Warna</h6>
                    <p class="text-secondary small mb-3">
                        Warna berdasarkan unit terjual.
                    </p>

                    @forelse ($penjualanWarna as $warna)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>{{ $warna->nama_warna }}</span>
                            <span class="fw-bold">
                                {{ number_format($warna->total_terjual, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-secondary text-center py-3 mb-0">
                            Belum ada data.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Grafik Analisis Penjualan --}}
    <div class="row g-3 mt-2">

        {{-- Grafik Kategori --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-1">Penjualan Berdasarkan Kategori</h6>
                    <p class="text-secondary small mb-3">
                        Jumlah unit terjual setiap kategori.
                    </p>

                    <div style="height:300px;">
                        <canvas id="grafikKategori"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik Ukuran --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-1">Penjualan Berdasarkan Ukuran</h6>
                    <p class="text-secondary small mb-3">
                        Jumlah unit terjual setiap ukuran.
                    </p>

                    <div style="height:300px;">
                        <canvas id="grafikUkuran"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik Warna --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-1">Penjualan Berdasarkan Warna</h6>
                    <p class="text-secondary small mb-3">
                        Proporsi unit terjual berdasarkan warna.
                    </p>

                    <div style="height:320px;">
                        <canvas id="grafikWarna"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>





    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const labelGrafik = @json($labelGrafik);
            const dataGrafik = @json($dataGrafik);

            const canvas = document.getElementById('grafikPenjualan');

            new Chart(canvas, {
                type: 'line',

                data: {
                    labels: labelGrafik,
                    datasets: [{
                        label: 'Pendapatan',
                        data: dataGrafik,
                        borderColor: '#B09A3E',
                        backgroundColor: 'rgba(201, 180, 88, 0.15)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#B09A3E'
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        },

                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Pendapatan: Rp ' +
                                        new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' +
                                        new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });

            // Data grafik kategori
            const kategoriLabels = @json($penjualanKategori->pluck('nama_kategori'));
            const kategoriData = @json($penjualanKategori->pluck('total_terjual'));

            // Data grafik ukuran
            const ukuranLabels = @json($penjualanUkuran->pluck('nama_ukuran'));
            const ukuranData = @json($penjualanUkuran->pluck('total_terjual'));

            // Data grafik warna
            const warnaLabels = @json($penjualanWarna->pluck('nama_warna'));
            const warnaData = @json($penjualanWarna->pluck('total_terjual'));

            // Grafik kategori
            new Chart(document.getElementById('grafikKategori'), {
                type: 'bar',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        label: 'Unit Terjual',
                        data: kategoriData,
                        backgroundColor: '#B09A3E',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Grafik ukuran
            new Chart(document.getElementById('grafikUkuran'), {
                type: 'bar',
                data: {
                    labels: ukuranLabels,
                    datasets: [{
                        label: 'Unit Terjual',
                        data: ukuranData,
                        backgroundColor: '#64748B',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Grafik warna
            new Chart(document.getElementById('grafikWarna'), {
                type: 'doughnut',
                data: {
                    labels: warnaLabels,
                    datasets: [{
                        label: 'Unit Terjual',
                        data: warnaData,
                        backgroundColor: [
                            '#B09A3E',
                            '#64748B',
                            '#A3A3A3',
                            '#C084FC',
                            '#60A5FA',
                            '#F59E0B',
                            '#34D399',
                            '#F87171'
                        ],
                        borderWidth: 2,
                        borderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });


        </script>

    @endpush



@endsection