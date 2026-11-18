<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')
            ->latest()
            ->paginate(10);

        return view('admin.produks.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::where('status', true)
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.produks.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'kategori_id' => [
                'required',
                Rule::exists('kategoris', 'id')->where('status', true),
            ],
            'deskripsi' => ['nullable', 'string'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'gambar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
        ]);

        do {
            $kodeProduk = 'PRD-' . strtoupper(Str::random(8));
        } while (Produk::where('kode_produk', $kodeProduk)->exists());

        $validated['kode_produk'] = $kodeProduk;

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('produk', 'public');
        }

        Produk::create($validated);

        return redirect()
            ->route('admin.produks.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::where('status', true)
            ->orWhere('id', $produk->kategori_id)
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.produks.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'kategori_id' => [
                'required',
                Rule::exists('kategoris', 'id'),
            ],
            'deskripsi' => ['nullable', 'string'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $validated['gambar'] = $request->file('gambar')
                ->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()
            ->route('admin.produks.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        // Lindungi riwayat varian dan transaksi produk.
        if ($produk->varianProduks()->exists()) {
            return back()->with(
                'error',
                'Produk tidak dapat dihapus karena sudah memiliki varian produk.'
            );
        }

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()
            ->route('admin.produks.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleStatus(Produk $produk)
    {
        $produk->update([
            'status' => !$produk->status,
        ]);

        return back()->with(
            'success',
            'Status produk berhasil diperbarui.'
        );
    }
}
