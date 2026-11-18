<?php

namespace App\Http\Controllers;

use App\Imports\PenjualanPreviewImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportPenjualanController extends Controller
{
    public function index()
    {
        return view('admin.import-penjualan.index');
    }

    public function downloadTemplate()
    {
        $path = public_path('templates/template_import_penjualan_miya.xlsx');

        if (!file_exists($path)) {
            return back()->with('error', 'File template Excel tidak ditemukan.');
        }

        return response()->download(
            $path,
            'template_import_penjualan_miya.xlsx'
        );
    }

    /**
     * Upload file Excel atau CSV.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv,txt',
                'max:10240',
            ],
        ], [
            'file.required' => 'Silakan pilih file terlebih dahulu.',
            'file.mimes' => 'Format file harus Excel atau CSV.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        // Simpan sementara, belum masuk database penjualan.
        $path = $request->file('file')->store('temp-import-penjualan');

        session([
            'import_penjualan_path' => $path,
        ]);

        return redirect()->route('admin.import-penjualan.preview')
            ->with('success', 'File berhasil diunggah. Silakan periksa preview data.');
    }

    /**
     * Menampilkan preview isi file.
     */
    public function preview()
    {
        $path = session('import_penjualan_path');

        if (!$path || !Storage::disk('local')->exists($path)) {
            return redirect()
                ->route('admin.import-penjualan.index')
                ->with('error', 'Silakan upload file terlebih dahulu.');
        }


        $sheets = Excel::toArray(
            new PenjualanPreviewImport(),
            Storage::disk('local')->path($path)
        );

        $requiredHeaders = [
            'tanggal_penjualan',
            'nomor_transaksi',
            'nama_produk',
            'kategori',
            'ukuran',
            'warna',
            'harga_jual',
            'jumlah',
            'diskon_transaksi',
        ];

        $rows = [];

        foreach ($sheets as $sheet) {
            $sheet = array_values(array_filter($sheet, function ($row) {
                return collect($row)->contains(function ($value) {
                    return $value !== null && $value !== '';
                });
            }));

            if (empty($sheet)) {
                continue;
            }

            $headers = array_keys($sheet[0]);

            if (empty(array_diff($requiredHeaders, $headers))) {
                // Sheet terakhir yang memiliki struktur kolom sesuai akan digunakan.
                $rows = $sheet;
            }
        }

        $actualHeaders = !empty($rows) ? array_keys($rows[0]) : [];

        $missingHeaders = array_values(array_diff(
            $requiredHeaders,
            $actualHeaders
        ));

        $totalRows = count($rows);

        $missingHeaders = array_values(array_diff(
            $requiredHeaders,
            $actualHeaders
        ));

        return view('admin.import-penjualan.preview', [
            'rows' => $rows,
            'totalRows' => count($rows),
            'missingHeaders' => $missingHeaders,
        ]);
    }
}
