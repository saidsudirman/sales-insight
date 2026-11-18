
@extends('layouts.admin')

@section('title', 'Tambah Varian Produk')
@section('page-title', 'Tambah Varian Produk')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Tambah Varian Produk</h4>
        <p class="text-muted mb-0">Tambahkan kombinasi produk, ukuran, dan warna.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data yang dimasukkan.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.varian-produks.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Produk <span class="text-danger">*</span></label>

                        <select name="produk_id"
                                class="form-select @error('produk_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Produk --</option>

                            @foreach($produks as $produk)
                                <option value="{{ $produk->id }}"
                                    {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                    {{ $produk->nama_produk }} ({{ $produk->kode_produk }})
                                </option>
                            @endforeach
                        </select>

                        @error('produk_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ukuran <span class="text-danger">*</span></label>

                        <select name="ukuran_id"
                                class="form-select @error('ukuran_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Ukuran --</option>

                            @foreach($ukurans as $ukuran)
                                <option value="{{ $ukuran->id }}"
                                    {{ old('ukuran_id') == $ukuran->id ? 'selected' : '' }}>
                                    {{ $ukuran->nama_ukuran }}
                                </option>
                            @endforeach
                        </select>

                        @error('ukuran_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Warna <span class="text-danger">*</span></label>

                        <select name="warna_id"
                                class="form-select @error('warna_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Warna --</option>

                            @foreach($warnas as $warna)
                                <option value="{{ $warna->id }}"
                                    {{ old('warna_id') == $warna->id ? 'selected' : '' }}>
                                    {{ $warna->nama_warna }}
                                </option>
                            @endforeach
                        </select>

                        @error('warna_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>

                        <input type="number"
                               name="stok"
                               class="form-control @error('stok') is-invalid @enderror"
                               value="{{ old('stok', 0) }}"
                               min="0"
                               required>

                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Stok Minimum <span class="text-danger">*</span></label>

                        <input type="number"
                               name="stok_minimum"
                               class="form-control @error('stok_minimum') is-invalid @enderror"
                               value="{{ old('stok_minimum', 5) }}"
                               min="0"
                               required>

                        @error('stok_minimum')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">Batas stok untuk menampilkan peringatan stok menipis.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>

                        <select name="status" class="form-select" required>
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                </div>

                <div class="alert alert-info mt-2">
                    <i class="fas fa-info-circle me-2"></i>
                    SKU akan dibuat otomatis berdasarkan kode produk, ukuran, dan warna yang dipilih.
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('admin.varian-produks.index') }}" class="btn btn-light border">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Varian
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
