@extends('layouts.admin')

@section('title', 'Data Produk')
@section('page-title', 'Data Produk')

@section('content')
    <div class="container-fluid px-0">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Data Produk</h4>
                <p class="text-muted mb-0">Kelola seluruh produk yang dijual.</p>
            </div>

            <a href="{{ route('admin.produks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Produk
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
                                <th>Gambar</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($produks as $produk)
                                <tr>
                                    <td>{{ $produks->firstItem() + $loop->index }}</td>

                                    <td>
                                        @if ($produk->gambar)
                                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                                width="65" height="65" style="
                                                                object-fit: cover;
                                                                border-radius: 8px;
                                                                border: 1px solid #dee2e6;
                                                            "
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                            <div style="
                                                            display: none;
                                                            width: 65px;
                                                            height: 65px;
                                                            background: #f1f5f9;
                                                            border-radius: 8px;
                                                            align-items: center;
                                                            justify-content: center;
                                                            color: #94a3b8;
                                                        ">
                                                <i class="fas fa-image fa-lg"></i>
                                            </div>
                                        @else
                                            <div style="
                                                            width: 65px;
                                                            height: 65px;
                                                            background: #f1f5f9;
                                                            border-radius: 8px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            color: #94a3b8;
                                                        ">
                                                <i class="fas fa-image fa-lg"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <small class="fw-semibold">{{ $produk->kode_produk }}</small>
                                    </td>

                                    <td class="fw-semibold">{{ $produk->nama_produk }}</td>

                                    <td>{{ $produk->kategori->nama_kategori ?? '-' }}</td>

                                    <td>Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</td>

                                    <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>

                                    <td>
                                        @if($produk->status)
                                            <span class="badge bg-success-subtle text-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.produks.edit', $produk->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.produks.toggle-status', $produk->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-info text-white"
                                                    title="Ubah Status">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.produks.destroy', $produk->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fas fa-box-open fa-2x mb-3 d-block"></i>
                                        Belum ada data produk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $produks->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
@endsection