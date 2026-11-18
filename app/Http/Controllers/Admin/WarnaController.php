<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warna;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarnaController extends Controller
{
    public function index()
    {
        $warnas = Warna::latest()->paginate(10);

        return view('admin.warnas.index', compact('warnas'));
    }

    public function create()
    {
        return view('admin.warnas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_warna' => [
                'required',
                'string',
                'max:255',
                'unique:warnas,nama_warna',
            ],
            'kode_warna' => [
                'nullable',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],
            'status' => ['required', 'boolean'],
        ]);

        Warna::create($validated);

        return redirect()
            ->route('admin.warnas.index')
            ->with('success', 'Warna berhasil ditambahkan.');
    }

    public function edit(Warna $warna)
    {
        return view('admin.warnas.edit', compact('warna'));
    }

    public function update(Request $request, Warna $warna)
    {
        $validated = $request->validate([
            'nama_warna' => [
                'required',
                'string',
                'max:255',
                Rule::unique('warnas', 'nama_warna')->ignore($warna->id),
            ],
            'kode_warna' => [
                'nullable',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],
            'status' => ['required', 'boolean'],
        ]);

        $warna->update($validated);

        return redirect()
            ->route('admin.warnas.index')
            ->with('success', 'Warna berhasil diperbarui.');
    }

    public function destroy(Warna $warna)
    {
        if ($warna->varianProduks()->exists()) {
            return back()->with(
                'error',
                'Warna tidak dapat dihapus karena masih digunakan oleh varian produk.'
            );
        }

        $warna->delete();

        return redirect()
            ->route('admin.warnas.index')
            ->with('success', 'Warna berhasil dihapus.');
    }

    public function toggleStatus(Warna $warna)
    {
        $warna->update([
            'status' => !$warna->status,
        ]);

        return back()->with(
            'success',
            'Status warna berhasil diperbarui.'
        );
    }
}
