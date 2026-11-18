<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pelanggans = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pelanggans.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('admin.pelanggans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:pelanggans,email'],
            'alamat' => ['nullable', 'string'],
        ]);

        Pelanggan::create($validated);

        return redirect()->route('admin.pelanggans.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('admin.pelanggans.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('pelanggans', 'email')->ignore($pelanggan->id),
            ],
            'alamat' => ['nullable', 'string'],
        ]);

        $pelanggan->update($validated);

        return redirect()->route('admin.pelanggans.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        if ($pelanggan->transaksis()->exists()) {
            return back()->with(
                'error',
                'Pelanggan tidak dapat dihapus karena sudah memiliki transaksi.'
            );
        }

        $pelanggan->delete();

        return redirect()->route('admin.pelanggans.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
