
@extends('layouts.admin')

@section('title', 'Varian Produk')
@section('page-title', 'Varian Produk')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Varian Produk</h4>
            <p class="text-muted mb-0">Kelola ukuran, warna, SKU, dan stok produk.</p>
        </div>

        <a href="{{ route('admin.varian-produks.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Varian
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Ukuran</th>
                            <th>Warna</th>
                            <th>Stok</th>
                            <th>Stok Minimum</th>
                            <th>Kondisi Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($varianProduks as $varian)
                            <tr>
                                <td>{{ $varianProduks->firstItem() + $loop->index }}</td>

                                <td class="fw-semibold">
                                    {{ $varian->produk->nama_produk ?? '-' }}
                                </td>

                                <td>
                                    <small class="fw-semibold">{{ $varian->sku }}</small>
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $varian->ukuran->nama_ukuran ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($varian->warna && $varian->warna->kode_warna)
                                            <span class="rounded-circle border"
                                                style="width:18px;height:18px;display:inline-block;background-color:{{ $varian->warna->kode_warna }};">
                                            </span>
                                        @endif

                                        {{ $varian->warna->nama_warna ?? '-' }}
                                    </div>
                                </td>

                                <td class="fw-bold">
                                    {{ number_format($varian->stok, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ number_format($varian->stok_minimum, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if($varian->stok <= $varian->stok_minimum)
                                        <span class="badge bg-danger-subtle text-danger">
                                            Stok Menipis
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success">
                                            Stok Aman
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($varian->status)
                                        <span class="badge bg-success-subtle text-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <a href="{{ route('admin.varian-produks.edit', $varian->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.varian-produks.toggle-status', $varian->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-sm btn-info text-white"
                                                    title="Ubah Status">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.varian-produks.destroy', $varian->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus varian ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="fas fa-boxes fa-2x mb-3 d-block"></i>
                                    Belum ada data varian produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $varianProduks->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>
@endsection
