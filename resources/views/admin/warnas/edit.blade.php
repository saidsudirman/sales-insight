
@extends('layouts.admin')

@section('title', 'Edit Warna')
@section('page-title', 'Edit Warna')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Warna</h4>
        <p class="text-muted mb-0">Perbarui informasi warna produk.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.warnas.update', $warna->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nama Warna <span class="text-danger">*</span>
                    </label>

                    <select name="nama_warna"
                            class="form-select @error('nama_warna') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih Warna --</option>

                        @foreach([
                            'Hitam',
                            'Putih',
                            'Merah',
                            'Biru',
                            'Navy',
                            'Hijau',
                            'Kuning',
                            'Abu-abu',
                            'Cokelat',
                            'Cream',
                            'Pink',
                            'Ungu',
                            'Orange',
                            'Maroon'
                        ] as $nama)
                            <option value="{{ $nama }}"
                                {{ old('nama_warna', $warna->nama_warna) == $nama ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>

                    @error('nama_warna')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Warna</label>

                    <div class="d-flex align-items-center gap-3">
                        <input type="color"
                               id="kode_warna"
                               name="kode_warna"
                               class="form-control form-control-color"
                               value="{{ old('kode_warna', $warna->kode_warna ?: '#000000') }}">

                        <span id="kode-preview" class="text-muted">
                            {{ old('kode_warna', $warna->kode_warna ?: '#000000') }}
                        </span>
                    </div>

                    @error('kode_warna')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>

                    <select name="status" class="form-select" required>
                        <option value="1"
                            {{ old('status', (string) (int) $warna->status) == '1' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0"
                            {{ old('status', (string) (int) $warna->status) == '0' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.warnas.index') }}"
                       class="btn btn-light border">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const colorInput = document.getElementById('kode_warna');
    const colorPreview = document.getElementById('kode-preview');

    colorInput.addEventListener('input', function () {
        colorPreview.textContent = this.value;
    });
</script>
@endpush
