
@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Data Pelanggan</h4>
            <p class="text-muted mb-0">Kelola informasi pelanggan MIYA.</p>
        </div>
        <a href="{{ route('admin.pelanggans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Pelanggan
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

            <form action="{{ route('admin.pelanggans.index') }}" method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-5">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Cari nama, nomor HP, atau email..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                        <a href="{{ route('admin.pelanggans.index') }}" class="btn btn-light border">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Pelanggan</th>
                            <th>Nomor HP</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggans as $pelanggan)
                            <tr>
                                <td>{{ $pelanggans->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $pelanggan->nama_pelanggan }}</td>
                                <td>{{ $pelanggan->no_hp ?: '-' }}</td>
                                <td>{{ $pelanggan->email ?: '-' }}</td>
                                <td>{{ $pelanggan->alamat ?: '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.pelanggans.edit', $pelanggan->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.pelanggans.destroy', $pelanggan->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-2x mb-3 d-block"></i>
                                    Belum ada data pelanggan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pelanggans->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>
@endsection
