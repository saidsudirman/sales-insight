<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Ukuran;
use App\Models\Warna;
use App\Models\VarianProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VarianProdukController extends Controller
{
    public function index()
    {
        $varianProduks = VarianProduk::with([
            'produk',
            'ukuran',
            'warna'
        ])->latest()->paginate(10);

        return view('admin.varian-produks.index', compact('varianProduks'));
    }

    public function create()
    {
        $produks = Produk::where('status', true)
            ->orderBy('nama_produk')
            ->get();

        $ukurans = Ukuran::where('status', true)
            ->orderBy('nama_ukuran')
            ->get();

        $warnas = Warna::where('status', true)
            ->orderBy('nama_warna')
            ->get();

        return view('admin.varian-produks.create', compact(
            'produks',
            'ukurans',
            'warnas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => [
                'required',
                Rule::exists('produks', 'id')->where('status', true),
            ],
            'ukuran_id' => [
                'required',
                Rule::exists('ukurans', 'id')->where('status', true),
            ],
            'warna_id' => [
                'required',
                Rule::exists('warnas', 'id')->where('status', true),
            ],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ]);

        $duplikat = VarianProduk::where('produk_id', $validated['produk_id'])
            ->where('ukuran_id', $validated['ukuran_id'])
            ->where('warna_id', $validated['warna_id'])
            ->exists();

        if ($duplikat) {
            return back()->withInput()->withErrors([
                'warna_id' => 'Kombinasi produk, ukuran, dan warna sudah tersedia.'
            ]);
        }

        $validated['sku'] = $this->generateSku(
            $validated['produk_id'],
            $validated['ukuran_id'],
            $validated['warna_id']
        );

        VarianProduk::create($validated);

        return redirect()
            ->route('admin.varian-produks.index')
            ->with('success', 'Varian produk berhasil ditambahkan.');
    }

    public function edit(VarianProduk $varianProduk)
    {
        $produks = Produk::where('status', true)
            ->orWhere('id', $varianProduk->produk_id)
            ->orderBy('nama_produk')
            ->get();

        $ukurans = Ukuran::where('status', true)
            ->orWhere('id', $varianProduk->ukuran_id)
            ->orderBy('nama_ukuran')
            ->get();

        $warnas = Warna::where('status', true)
            ->orWhere('id', $varianProduk->warna_id)
            ->orderBy('nama_warna')
            ->get();

        return view('admin.varian-produks.edit', compact(
            'varianProduk',
            'produks',
            'ukurans',
            'warnas'
        ));
    }

    public function update(Request $request, VarianProduk $varianProduk)
    {
        $validated = $request->validate([
            'produk_id' => ['required', Rule::exists('produks', 'id')],
            'ukuran_id' => ['required', Rule::exists('ukurans', 'id')],
            'warna_id' => ['required', Rule::exists('warnas', 'id')],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ]);

        $duplikat = VarianProduk::where('produk_id', $validated['produk_id'])
            ->where('ukuran_id', $validated['ukuran_id'])
            ->where('warna_id', $validated['warna_id'])
            ->where('id', '!=', $varianProduk->id)
            ->exists();

        if ($duplikat) {
            return back()->withInput()->withErrors([
                'warna_id' => 'Kombinasi produk, ukuran, dan warna sudah digunakan.'
            ]);
        }

        $kombinasiBerubah =
            $varianProduk->produk_id != $validated['produk_id'] ||
            $varianProduk->ukuran_id != $validated['ukuran_id'] ||
            $varianProduk->warna_id != $validated['warna_id'];

        if ($kombinasiBerubah) {
            $validated['sku'] = $this->generateSku(
                $validated['produk_id'],
                $validated['ukuran_id'],
                $validated['warna_id']
            );
        }

        $varianProduk->update($validated);

        return redirect()
            ->route('admin.varian-produks.index')
            ->with('success', 'Varian produk berhasil diperbarui.');
    }

    public function destroy(VarianProduk $varianProduk)
    {
        if ($varianProduk->detailTransaksis()->exists()) {
            return back()->with(
                'error',
                'Varian tidak dapat dihapus karena sudah digunakan dalam transaksi.'
            );
        }

        $varianProduk->delete();

        return redirect()
            ->route('admin.varian-produks.index')
            ->with('success', 'Varian produk berhasil dihapus.');
    }

    public function toggleStatus(VarianProduk $varianProduk)
    {
        $varianProduk->update([
            'status' => !$varianProduk->status
        ]);

        return back()->with('success', 'Status varian produk berhasil diperbarui.');
    }

    private function generateSku($produkId, $ukuranId, $warnaId)
    {
        $produk = Produk::findOrFail($produkId);
        $ukuran = Ukuran::findOrFail($ukuranId);
        $warna = Warna::findOrFail($warnaId);

        $kodeProduk = strtoupper($produk->kode_produk);
        $kodeUkuran = strtoupper(Str::slug($ukuran->nama_ukuran, ''));
        $kodeWarna = strtoupper(Str::slug($warna->nama_warna, ''));

        return $kodeProduk . '-' . $kodeUkuran . '-' . $kodeWarna;
    }
}
