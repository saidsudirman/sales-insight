<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ukuran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UkuranController extends Controller
{
    public function index()
    {
        $ukurans = Ukuran::latest()->paginate(10);

        return view('admin.ukurans.index', compact('ukurans'));
    }

    public function create()
    {
        return view('admin.ukurans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ukuran' => [
                'required',
                'string',
                'max:255',
                'unique:ukurans,nama_ukuran',
            ],
            'status' => ['required', 'boolean'],
        ]);

        Ukuran::create($validated);

        return redirect()
            ->route('admin.ukurans.index')
            ->with('success', 'Ukuran berhasil ditambahkan.');
    }

    public function edit(Ukuran $ukuran)
    {
        return view('admin.ukurans.edit', compact('ukuran'));
    }

    public function update(Request $request, Ukuran $ukuran)
    {
        $validated = $request->validate([
            'nama_ukuran' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ukurans', 'nama_ukuran')
                    ->ignore($ukuran->id),
            ],
            'status' => ['required', 'boolean'],
        ]);

        $ukuran->update($validated);

        return redirect()
            ->route('admin.ukurans.index')
            ->with('success', 'Ukuran berhasil diperbarui.');
    }

    public function destroy(Ukuran $ukuran)
    {
        if ($ukuran->varianProduks()->exists()) {
            return back()->with(
                'error',
                'Ukuran tidak dapat dihapus karena masih digunakan oleh varian produk.'
            );
        }

        $ukuran->delete();

        return redirect()
            ->route('admin.ukurans.index')
            ->with('success', 'Ukuran berhasil dihapus.');
    }

    public function toggleStatus(Ukuran $ukuran)
    {
        $ukuran->update([
            'status' => !$ukuran->status,
        ]);

        return back()->with(
            'success',
            'Status ukuran berhasil diperbarui.'
        );
    }
}
