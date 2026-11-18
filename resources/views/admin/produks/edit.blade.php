
@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Produk</h4>
        <p class="text-muted mb-0">Perbarui informasi produk.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.produks.update', $produk->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Produk</label>
                    <input type="text"
                           class="form-control bg-light"
                           value="{{ $produk->kode_produk }}"
                           readonly>
                    <small class="text-muted">Kode produk dibuat otomatis dan tidak dapat diubah.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text"
                               name="nama_produk"
                               class="form-control @error('nama_produk') is-invalid @enderror"
                               value="{{ old('nama_produk', $produk->nama_produk) }}"
                               required>
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id"
                                class="form-select @error('kategori_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ old('kategori_id', $produk->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi"
                              class="form-control"
                              rows="3">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Harga Beli (Rp) <span class="text-danger">*</span></label>
                        <input type="number"
                               name="harga_beli"
                               class="form-control @error('harga_beli') is-invalid @enderror"
                               value="{{ old('harga_beli', $produk->harga_beli) }}"
                               min="0"
                               step="0.01"
                               required>
                        @error('harga_beli')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                        <input type="number"
                               name="harga_jual"
                               class="form-control @error('harga_jual') is-invalid @enderror"
                               value="{{ old('harga_jual', $produk->harga_jual) }}"
                               min="0"
                               step="0.01"
                               required>
                        @error('harga_jual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar Produk</label>

                    @if($produk->gambar)
                        <div class="mb-3">
                            <img id="preview-gambar"
                                 src="{{ asset('storage/' . $produk->gambar) }}"
                                 alt="{{ $produk->nama_produk }}"
                                 class="rounded border"
                                 style="width:150px;height:150px;object-fit:cover;">
                        </div>
                    @else
                        <div class="mb-3">
                            <img id="preview-gambar"
                                 src="#"
                                 alt="Preview gambar"
                                 class="rounded border d-none"
                                 style="width:150px;height:150px;object-fit:cover;">
                        </div>
                    @endif

                    <input type="file"
                           name="gambar"
                           id="gambar"
                           class="form-control @error('gambar') is-invalid @enderror"
                           accept=".jpg,.jpeg,.png,.webp">

                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar. Maksimal 2 MB.</small>

                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="1"
                            {{ old('status', (string) (int) $produk->status) == '1' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="0"
                            {{ old('status', (string) (int) $produk->status) == '0' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.produks.index') }}"
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
    document.getElementById('gambar').addEventListener('change', function(event) {
        const preview = document.getElementById('preview-gambar');
        const file = event.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        }
    });
</script>
@endpush
