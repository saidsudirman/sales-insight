
@extends('layouts.admin')

@section('title', 'Edit Ukuran')
@section('page-title', 'Edit Ukuran')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Ukuran</h4>
        <p class="text-muted mb-0">Perbarui informasi ukuran produk.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.ukurans.update', $ukuran->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nama Ukuran <span class="text-danger">*</span>
                    </label>

                    <select name="nama_ukuran"
                            class="form-select @error('nama_ukuran') is-invalid @enderror"
                            required>

                        <option value="">-- Pilih Ukuran --</option>

                        @foreach([
                            'XS',
                            'S',
                            'M',
                            'L',
                            'XL',
                            'XXL',
                            'XXXL',
                            '4XL',
                            '5XL',
                            'All Size'
                        ] as $size)

                            <option value="{{ $size }}"
                                {{ old('nama_ukuran', $ukuran->nama_ukuran) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>

                        @endforeach

                    </select>

                    @error('nama_ukuran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>

                    <select name="status" class="form-select" required>
                        <option value="1"
                            {{ old('status', (string) (int) $ukuran->status) == '1' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0"
                            {{ old('status', (string) (int) $ukuran->status) == '0' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.ukurans.index') }}"
                       class="btn btn-light border">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
