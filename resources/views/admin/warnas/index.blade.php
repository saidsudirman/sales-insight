
@extends('layouts.admin')

@section('title', 'Data Warna')
@section('page-title', 'Data Warna')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Data Warna</h4>
            <p class="text-muted mb-0">Kelola warna produk.</p>
        </div>

        <a href="{{ route('admin.warnas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Warna
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
                            <th width="60">No</th>
                            <th>Nama Warna</th>
                            <th>Kode Warna</th>
                            <th>Preview</th>
                            <th>Status</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($warnas as $warna)
                            <tr>
                                <td>{{ $warnas->firstItem() + $loop->index }}</td>

                                <td class="fw-semibold">
                                    {{ $warna->nama_warna }}
                                </td>

                                <td>
                                    {{ $warna->kode_warna ?? '-' }}
                                </td>

                                <td>
                                    @if($warna->kode_warna)
                                        <span class="d-inline-block rounded border"
                                              style="width: 30px; height: 30px; background-color: {{ e($warna->kode_warna) }};"></span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($warna->status)
                                        <span class="badge bg-success-subtle text-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <a href="{{ route('admin.warnas.edit', $warna->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.warnas.toggle-status', $warna->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-sm btn-info text-white"
                                                    title="Ubah Status">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.warnas.destroy', $warna->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus warna ini?')">
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-palette fa-2x mb-3 d-block"></i>
                                    Belum ada data warna.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $warnas->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>
@endsection
