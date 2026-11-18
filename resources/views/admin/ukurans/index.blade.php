
@extends('layouts.admin')

@section('title', 'Data Ukuran')
@section('page-title', 'Data Ukuran')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Data Ukuran</h4>
            <p class="text-muted mb-0">Kelola ukuran produk.</p>
        </div>

        <a href="{{ route('admin.ukurans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Ukuran
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
                            <th width="70">No</th>
                            <th>Nama Ukuran</th>
                            <th>Status</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ukurans as $ukuran)
                            <tr>
                                <td>{{ $ukurans->firstItem() + $loop->index }}</td>

                                <td class="fw-semibold">
                                    {{ $ukuran->nama_ukuran }}
                                </td>

                                <td>
                                    @if($ukuran->status)
                                        <span class="badge bg-success-subtle text-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.ukurans.edit', $ukuran->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.ukurans.toggle-status', $ukuran->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-sm btn-info text-white"
                                                    title="Ubah Status">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.ukurans.destroy', $ukuran->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus ukuran ini?')">
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
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-ruler fa-2x mb-3 d-block"></i>
                                    Belum ada data ukuran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $ukurans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
