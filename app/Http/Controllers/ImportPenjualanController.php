<?php

namespace App\Http\Controllers;

use App\Imports\PenjualanPreviewImport;
use Carbon\Carbon;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Ukuran;
use App\Models\Warna;
use App\Models\VarianProduk;
use Illuminate\Support\Str;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

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

        $path = $request->file('file')->store('temp-import-penjualan');

        session([
            'import_penjualan_path' => $path,
        ]);

        return redirect()
            ->route('admin.import-penjualan.preview')
            ->with('success', 'File berhasil diunggah. Silakan periksa preview data.');
    }

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

        // Cari sheet yang mempunyai seluruh kolom template.
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
                $rows = $sheet;
                break;
            }
        }

        $actualHeaders = !empty($rows)
            ? array_keys($rows[0])
            : [];

        $missingHeaders = array_values(array_diff(
            $requiredHeaders,
            $actualHeaders
        ));

        $validatedRows = [];
        $totalValid = 0;
        $totalInvalid = 0;

        // Validasi hanya dilakukan jika seluruh kolom tersedia.
        if (empty($missingHeaders)) {
            foreach ($rows as $index => $row) {
                $errors = [];

                // 1. Validasi tanggal.
                $tanggalAsli = $row['tanggal_penjualan'] ?? null;
                $tanggal = null;

                try {
                    if ($tanggalAsli === null || trim((string) $tanggalAsli) === '') {
                        $errors[] = 'Tanggal penjualan wajib diisi.';
                    } elseif (is_numeric($tanggalAsli)) {
                        $tanggal = ExcelDate::excelToDateTimeObject(
                            (float) $tanggalAsli
                        )->format('Y-m-d');
                    } else {
                        $tanggal = Carbon::parse($tanggalAsli)->format('Y-m-d');
                    }
                } catch (Throwable $e) {
                    $errors[] = 'Format tanggal penjualan tidak valid.';
                }

                // 2. Validasi nomor transaksi.
                $nomorTransaksi = trim((string) ($row['nomor_transaksi'] ?? ''));

                if ($nomorTransaksi === '') {
                    $errors[] = 'Nomor transaksi wajib diisi.';
                }

                // 3. Validasi produk.
                $namaProduk = trim((string) ($row['nama_produk'] ?? ''));

                $produk = null;

                if ($namaProduk === '') {
                    $errors[] = 'Nama produk wajib diisi.';
                } else {
                    $produk = DB::table('produks')
                        ->whereRaw('LOWER(TRIM(nama_produk)) = ?', [
                            mb_strtolower($namaProduk)
                        ])
                        ->first();

                    if (!$produk) {
                        $errors[] = "Produk '{$namaProduk}' belum terdaftar.";
                    }
                }

                // 4. Validasi kategori.
                $namaKategori = trim((string) ($row['kategori'] ?? ''));

                $kategori = null;

                if ($namaKategori === '') {
                    $errors[] = 'Kategori wajib diisi.';
                } else {
                    $kategori = DB::table('kategoris')
                        ->whereRaw('LOWER(TRIM(nama_kategori)) = ?', [
                            mb_strtolower($namaKategori)
                        ])
                        ->first();

                    if (!$kategori) {
                        $errors[] = "Kategori '{$namaKategori}' belum terdaftar.";
                    } elseif ($produk && $produk->kategori_id != $kategori->id) {
                        $errors[] = 'Kategori tidak sesuai dengan produk.';
                    }
                }

                // 5. Validasi ukuran.
                $namaUkuran = trim((string) ($row['ukuran'] ?? ''));

                $ukuran = null;

                if ($namaUkuran === '') {
                    $errors[] = 'Ukuran wajib diisi.';
                } else {
                    $ukuran = DB::table('ukurans')
                        ->whereRaw('LOWER(TRIM(nama_ukuran)) = ?', [
                            mb_strtolower($namaUkuran)
                        ])
                        ->first();

                    if (!$ukuran) {
                        $errors[] = "Ukuran '{$namaUkuran}' belum terdaftar.";
                    }
                }

                // 6. Validasi warna.
                $namaWarna = trim((string) ($row['warna'] ?? ''));

                $warna = null;

                if ($namaWarna === '') {
                    $errors[] = 'Warna wajib diisi.';
                } else {
                    $warna = DB::table('warnas')
                        ->whereRaw('LOWER(TRIM(nama_warna)) = ?', [
                            mb_strtolower($namaWarna)
                        ])
                        ->first();

                    if (!$warna) {
                        $errors[] = "Warna '{$namaWarna}' belum terdaftar.";
                    }
                }

                // 7. Validasi kombinasi varian produk.
                if ($produk && $ukuran && $warna) {
                    $varian = DB::table('varian_produks')
                        ->where('produk_id', $produk->id)
                        ->where('ukuran_id', $ukuran->id)
                        ->where('warna_id', $warna->id)
                        ->first();

                    if (!$varian) {
                        $errors[] = 'Kombinasi produk, ukuran, dan warna belum terdaftar.';
                    }
                }

                // 8. Validasi harga jual.
                $harga = $row['harga_jual'] ?? null;

                if (!is_numeric($harga) || $harga < 0) {
                    $errors[] = 'Harga jual harus berupa angka minimal 0.';
                }

                // 9. Validasi jumlah.
                $jumlah = $row['jumlah'] ?? null;

                if (
                    !is_numeric($jumlah) ||
                    (float) $jumlah <= 0 ||
                    (float) $jumlah != (int) $jumlah
                ) {
                    $errors[] = 'Jumlah harus berupa bilangan bulat lebih dari 0.';
                }

                // 10. Validasi diskon.
                $diskon = $row['diskon_transaksi'] ?? null;

                if (!is_numeric($diskon) || $diskon < 0) {
                    $errors[] = 'Diskon transaksi harus berupa angka minimal 0.';
                }

                // Format tanggal untuk ditampilkan.
                $row['tanggal_penjualan'] = $tanggal ?? $tanggalAsli;

                $isValid = empty($errors);

                if ($isValid) {
                    $totalValid++;
                } else {
                    $totalInvalid++;
                }

                $validatedRows[] = [
                    'data' => $row,
                    'errors' => $errors,
                    'is_valid' => $isValid,
                ];
            }
        }

        return view('admin.import-penjualan.preview', [
            'rows' => $rows,
            'validatedRows' => $validatedRows,
            'totalRows' => count($rows),
            'totalValid' => $totalValid,
            'totalInvalid' => $totalInvalid,
            'missingHeaders' => $missingHeaders,
        ]);
    }

    public function prepareMasterData()
    {
        $path = session('import_penjualan_path');

        if (!$path || !Storage::disk('local')->exists($path)) {
            return redirect()
                ->route('admin.import-penjualan.index')
                ->with('error', 'File Excel tidak ditemukan. Silakan upload ulang.');
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
                $rows = $sheet;
                break;
            }
        }

        if (empty($rows)) {
            return back()->with('error', 'Data Excel tidak ditemukan atau format kolom tidak sesuai.');
        }

        $created = [
            'kategori' => 0,
            'ukuran' => 0,
            'warna' => 0,
            'produk' => 0,
            'varian' => 0,
        ];

        try {
            DB::transaction(function () use ($rows, &$created) {

                foreach ($rows as $index => $row) {
                    $nomorBaris = $index + 2;

                    $namaProduk = trim((string) ($row['nama_produk'] ?? ''));
                    $namaKategori = trim((string) ($row['kategori'] ?? ''));
                    $namaUkuran = trim((string) ($row['ukuran'] ?? ''));
                    $namaWarna = trim((string) ($row['warna'] ?? ''));

                    if (
                        $namaProduk === '' ||
                        $namaKategori === '' ||
                        $namaUkuran === '' ||
                        $namaWarna === ''
                    ) {
                        throw new \RuntimeException(
                            "Nama produk, kategori, ukuran, dan warna wajib diisi pada baris {$nomorBaris}."
                        );
                    }

                    // 1. Siapkan kategori.
                    $kategori = Kategori::whereRaw(
                        'LOWER(TRIM(nama_kategori)) = ?',
                        [mb_strtolower($namaKategori)]
                    )->first();

                    if (!$kategori) {
                        $kategori = Kategori::create([
                            'nama_kategori' => $namaKategori,
                            'deskripsi' => null,
                            'status' => true,
                        ]);

                        $created['kategori']++;
                    }

                    // 2. Siapkan ukuran.
                    $ukuran = Ukuran::whereRaw(
                        'LOWER(TRIM(nama_ukuran)) = ?',
                        [mb_strtolower($namaUkuran)]
                    )->first();

                    if (!$ukuran) {
                        $ukuran = Ukuran::create([
                            'nama_ukuran' => $namaUkuran,
                            'status' => true,
                        ]);

                        $created['ukuran']++;
                    }

                    // 3. Siapkan warna.
                    $warna = Warna::whereRaw(
                        'LOWER(TRIM(nama_warna)) = ?',
                        [mb_strtolower($namaWarna)]
                    )->first();

                    if (!$warna) {
                        $warna = Warna::create([
                            'nama_warna' => $namaWarna,
                            'kode_warna' => null,
                            'status' => true,
                        ]);

                        $created['warna']++;
                    }

                    // 4. Siapkan produk.
                    $produk = Produk::whereRaw(
                        'LOWER(TRIM(nama_produk)) = ?',
                        [mb_strtolower($namaProduk)]
                    )->first();

                    if ($produk && $produk->kategori_id != $kategori->id) {
                        throw new \RuntimeException(
                            "Kategori produk {$namaProduk} tidak sesuai dengan kategori di master data pada baris {$nomorBaris}."
                        );
                    }

                    if (!$produk) {
                        do {
                            $kodeProduk = 'PRD-' . strtoupper(Str::random(8));
                        } while (Produk::where('kode_produk', $kodeProduk)->exists());

                        $hargaJual = is_numeric($row['harga_jual'] ?? null)
                            ? max(0, (float) $row['harga_jual'])
                            : 0;

                        $produk = Produk::create([
                            'kategori_id' => $kategori->id,
                            'kode_produk' => $kodeProduk,
                            'nama_produk' => $namaProduk,
                            'deskripsi' => null,
                            'harga_beli' => 0,
                            'harga_jual' => $hargaJual,
                            'gambar' => null,
                            'status' => true,
                        ]);

                        $created['produk']++;
                    }

                    // 5. Siapkan kombinasi varian.
                    $varian = VarianProduk::where('produk_id', $produk->id)
                        ->where('ukuran_id', $ukuran->id)
                        ->where('warna_id', $warna->id)
                        ->first();

                    if (!$varian) {
                        $sku = strtoupper($produk->kode_produk)
                            . '-' . strtoupper(Str::slug($ukuran->nama_ukuran, ''))
                            . '-' . strtoupper(Str::slug($warna->nama_warna, ''));

                        // Pastikan SKU tidak bertabrakan dengan varian lain.
                        if (VarianProduk::where('sku', $sku)->exists()) {
                            throw new \RuntimeException(
                                "SKU {$sku} sudah digunakan oleh kombinasi varian lain."
                            );
                        }

                        VarianProduk::create([
                            'produk_id' => $produk->id,
                            'ukuran_id' => $ukuran->id,
                            'warna_id' => $warna->id,
                            'sku' => $sku,
                            'stok' => 0,
                            'stok_minimum' => 5,
                            'status' => true,
                        ]);

                        $created['varian']++;
                    }
                }
            });

        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.import-penjualan.preview')
                ->with('error', 'Persiapan master data gagal: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.import-penjualan.preview')
            ->with('success', 'Persiapan master data berhasil. Kategori: '
                . $created['kategori']
                . ', ukuran: ' . $created['ukuran']
                . ', warna: ' . $created['warna']
                . ', produk: ' . $created['produk']
                . ', varian: ' . $created['varian']
                . '. Silakan periksa kembali hasil validasi.');
    }


    public function confirmImport()
    {
        $path = session('import_penjualan_path');

        if (!$path || !Storage::disk('local')->exists($path)) {
            return redirect()
                ->route('admin.import-penjualan.index')
                ->with('error', 'File Excel tidak ditemukan. Silakan upload ulang.');
        }

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

        try {
            $sheets = Excel::toArray(
                new PenjualanPreviewImport(),
                Storage::disk('local')->path($path)
            );

            $rows = [];

            foreach ($sheets as $sheet) {
                $sheet = array_values(array_filter($sheet, function ($row) {
                    return collect($row)->contains(
                        fn($value) => $value !== null && $value !== ''
                    );
                }));

                if (empty($sheet)) {
                    continue;
                }

                $headers = array_keys($sheet[0]);

                if (empty(array_diff($requiredHeaders, $headers))) {
                    $rows = $sheet;
                    break;
                }
            }

            if (empty($rows)) {
                return back()->with('error', 'Data Excel tidak ditemukan atau format kolom tidak sesuai.');
            }

            // Validasi ulang sebelum menyimpan.
            $groupedTransactions = [];

            foreach ($rows as $index => $row) {
                $line = $index + 2;

                $nomor = trim((string) ($row['nomor_transaksi'] ?? ''));
                $namaProduk = trim((string) ($row['nama_produk'] ?? ''));
                $namaKategori = trim((string) ($row['kategori'] ?? ''));
                $namaUkuran = trim((string) ($row['ukuran'] ?? ''));
                $namaWarna = trim((string) ($row['warna'] ?? ''));

                if (
                    $nomor === '' ||
                    $namaProduk === '' ||
                    $namaKategori === '' ||
                    $namaUkuran === '' ||
                    $namaWarna === ''
                ) {
                    throw new \RuntimeException(
                        "Ada data wajib yang kosong pada baris Excel {$line}."
                    );
                }

                // Tanggal.
                $tanggalAsli = $row['tanggal_penjualan'] ?? null;

                if ($tanggalAsli === null || trim((string) $tanggalAsli) === '') {
                    throw new \RuntimeException("Tanggal kosong pada baris {$line}.");
                }

                try {
                    if (is_numeric($tanggalAsli)) {
                        $tanggal = ExcelDate::excelToDateTimeObject(
                            (float) $tanggalAsli
                        )->format('Y-m-d');
                    } else {
                        $tanggal = Carbon::parse($tanggalAsli)->format('Y-m-d');
                    }
                } catch (Throwable $e) {
                    throw new \RuntimeException("Tanggal tidak valid pada baris {$line}.");
                }

                // Harga dan jumlah.
                $harga = $row['harga_jual'] ?? null;
                $jumlah = $row['jumlah'] ?? null;
                $diskon = $row['diskon_transaksi'] ?? null;

                if (!is_numeric($harga) || $harga < 0) {
                    throw new \RuntimeException("Harga tidak valid pada baris {$line}.");
                }

                if (
                    !is_numeric($jumlah) ||
                    (float) $jumlah <= 0 ||
                    (float) $jumlah != (int) $jumlah
                ) {
                    throw new \RuntimeException("Jumlah tidak valid pada baris {$line}.");
                }

                if (!is_numeric($diskon) || $diskon < 0) {
                    throw new \RuntimeException("Diskon tidak valid pada baris {$line}.");
                }

                // Cari master data.
                $produk = Produk::whereRaw(
                    'LOWER(TRIM(nama_produk)) = ?',
                    [mb_strtolower($namaProduk)]
                )->first();

                $kategori = Kategori::whereRaw(
                    'LOWER(TRIM(nama_kategori)) = ?',
                    [mb_strtolower($namaKategori)]
                )->first();

                $ukuran = Ukuran::whereRaw(
                    'LOWER(TRIM(nama_ukuran)) = ?',
                    [mb_strtolower($namaUkuran)]
                )->first();

                $warna = Warna::whereRaw(
                    'LOWER(TRIM(nama_warna)) = ?',
                    [mb_strtolower($namaWarna)]
                )->first();

                if (!$produk || !$kategori || !$ukuran || !$warna) {
                    throw new \RuntimeException(
                        "Master data belum lengkap pada baris {$line}."
                    );
                }

                if ($produk->kategori_id != $kategori->id) {
                    throw new \RuntimeException(
                        "Kategori produk tidak sesuai pada baris {$line}."
                    );
                }

                $varian = VarianProduk::where('produk_id', $produk->id)
                    ->where('ukuran_id', $ukuran->id)
                    ->where('warna_id', $warna->id)
                    ->first();

                if (!$varian) {
                    throw new \RuntimeException(
                        "Varian produk belum tersedia pada baris {$line}."
                    );
                }

                $subtotal = (float) $harga * (int) $jumlah;

                if (!isset($groupedTransactions[$nomor])) {
                    $groupedTransactions[$nomor] = [
                        'tanggal' => $tanggal,
                        'diskon' => (float) $diskon,
                        'details' => [],
                    ];
                } else {
                    if ($groupedTransactions[$nomor]['tanggal'] !== $tanggal) {
                        throw new \RuntimeException(
                            "Nomor transaksi {$nomor} memiliki tanggal berbeda."
                        );
                    }

                    // Diskon transaksi harus dicantumkan satu kali saja.
                    if ((float) $diskon > 0) {
                        throw new \RuntimeException(
                            "Diskon transaksi {$nomor} tercatat lebih dari satu kali. Isi diskon hanya pada baris pertama transaksi."
                        );
                    }
                }

                $groupedTransactions[$nomor]['details'][] = [
                    'varian_produk_id' => $varian->id,
                    'jumlah' => (int) $jumlah,
                    'harga_satuan' => (float) $harga,
                    'subtotal' => $subtotal,
                ];
            }

            if (empty($groupedTransactions)) {
                throw new \RuntimeException('Tidak ada data transaksi untuk diimpor.');
            }

            // Cegah duplikasi nomor transaksi.
            $nomorTransaksis = array_keys($groupedTransactions);

            $existing = Transaksi::whereIn(
                'nomor_transaksi',
                $nomorTransaksis
            )->pluck('nomor_transaksi')->all();

            if (!empty($existing)) {
                throw new \RuntimeException(
                    'Nomor transaksi berikut sudah pernah diimpor: '
                    . implode(', ', $existing)
                );
            }

            $totalTransaksi = 0;
            $totalDetail = 0;

            DB::transaction(function () use ($groupedTransactions, &$totalTransaksi, &$totalDetail) {
                foreach ($groupedTransactions as $nomor => $transactionData) {
                    $totalHarga = collect($transactionData['details'])
                        ->sum('subtotal');

                    $diskon = $transactionData['diskon'];

                    if ($diskon > $totalHarga) {
                        throw new \RuntimeException(
                            "Diskon transaksi {$nomor} melebihi total harga."
                        );
                    }

                    $transaksi = Transaksi::create([
                        'pelanggan_id' => null,
                        'user_id' => auth()->id(),
                        'nomor_transaksi' => $nomor,
                        'tanggal_transaksi' => $transactionData['tanggal'],
                        'total_harga' => $totalHarga,
                        'diskon' => $diskon,
                        'grand_total' => $totalHarga - $diskon,
                        'metode_pembayaran' => null,
                        'catatan' => 'Diimpor dari data historis Excel.',
                    ]);

                    foreach ($transactionData['details'] as $detail) {
                        DetailTransaksi::create([
                            'transaksi_id' => $transaksi->id,
                            'varian_produk_id' => $detail['varian_produk_id'],
                            'jumlah' => $detail['jumlah'],
                            'harga_satuan' => $detail['harga_satuan'],
                            'subtotal' => $detail['subtotal'],
                        ]);

                        $totalDetail++;
                    }

                    $totalTransaksi++;
                }
            });

            // Hapus file sementara setelah import berhasil.
            Storage::disk('local')->delete($path);
            session()->forget('import_penjualan_path');

            return redirect()
                ->route('admin.import-penjualan.index')
                ->with(
                    'success',
                    "Import berhasil! {$totalTransaksi} transaksi dan {$totalDetail} detail transaksi berhasil disimpan."
                );

        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.import-penjualan.preview')
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }


}
