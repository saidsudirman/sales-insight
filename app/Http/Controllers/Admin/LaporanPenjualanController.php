<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanPenjualanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
        ]);

        $tanggalMulai = $validated['tanggal_mulai'] ?? null;
        $tanggalAkhir = $validated['tanggal_akhir'] ?? null;

        if ($tanggalMulai && $tanggalAkhir && $tanggalAkhir < $tanggalMulai) {
            return back()
                ->withErrors([
                    'tanggal_akhir' => 'Tanggal akhir tidak boleh sebelum tanggal mulai.'
                ])
                ->withInput();
        }

        // Query transaksi berdasarkan periode
        $queryTransaksi = DB::table('transaksis');

        if ($tanggalMulai) {
            $queryTransaksi->whereDate('tanggal_transaksi', '>=', $tanggalMulai);
        }

        if ($tanggalAkhir) {
            $queryTransaksi->whereDate('tanggal_transaksi', '<=', $tanggalAkhir);
        }

        // Ringkasan laporan
        $totalPendapatan = (clone $queryTransaksi)->sum('grand_total');

        $jumlahTransaksi = (clone $queryTransaksi)->count();

        // Query detail penjualan
        $detailQuery = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('varian_produks', 'detail_transaksis.varian_produk_id', '=', 'varian_produks.id')
            ->join('produks', 'varian_produks.produk_id', '=', 'produks.id')
            ->join('kategoris', 'produks.kategori_id', '=', 'kategoris.id')
            ->join('ukurans', 'varian_produks.ukuran_id', '=', 'ukurans.id')
            ->join('warnas', 'varian_produks.warna_id', '=', 'warnas.id');

        if ($tanggalMulai) {
            $detailQuery->whereDate('transaksis.tanggal_transaksi', '>=', $tanggalMulai);
        }

        if ($tanggalAkhir) {
            $detailQuery->whereDate('transaksis.tanggal_transaksi', '<=', $tanggalAkhir);
        }

        $produkTerjual = (clone $detailQuery)
            ->sum('detail_transaksis.jumlah');

        // Data tabel laporan
        $laporan = $detailQuery
            ->select(
                'transaksis.nomor_transaksi',
                'transaksis.tanggal_transaksi',
                'produks.nama_produk',
                'kategoris.nama_kategori',
                'ukurans.nama_ukuran',
                'warnas.nama_warna',
                'detail_transaksis.jumlah',
                'detail_transaksis.harga_satuan',
                'detail_transaksis.subtotal'
            )
            ->orderByDesc('transaksis.tanggal_transaksi')
            ->orderBy('transaksis.nomor_transaksi')
            ->paginate(15)
            ->withQueryString();

        $rataRataTransaksi = $jumlahTransaksi > 0
            ? $totalPendapatan / $jumlahTransaksi
            : 0;

        return view('admin.laporan-penjualan.index', compact(
            'tanggalMulai',
            'tanggalAkhir',
            'totalPendapatan',
            'jumlahTransaksi',
            'produkTerjual',
            'rataRataTransaksi',
            'laporan'
        ));
    }

    public function exportPdf(Request $request)
{
    $validated = $request->validate([
        'tanggal_mulai' => 'nullable|date',
        'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
    ]);

    $tanggalMulai = $validated['tanggal_mulai'] ?? null;
    $tanggalAkhir = $validated['tanggal_akhir'] ?? null;

    $queryTransaksi = DB::table('transaksis');

    if ($tanggalMulai) {
        $queryTransaksi->whereDate(
            'tanggal_transaksi', '>=', $tanggalMulai
        );
    }

    if ($tanggalAkhir) {
        $queryTransaksi->whereDate(
            'tanggal_transaksi', '<=', $tanggalAkhir
        );
    }

    $totalPendapatan = (clone $queryTransaksi)->sum('grand_total');
    $jumlahTransaksi = (clone $queryTransaksi)->count();

    $detailQuery = DB::table('detail_transaksis')
        ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
        ->join('varian_produks', 'detail_transaksis.varian_produk_id', '=', 'varian_produks.id')
        ->join('produks', 'varian_produks.produk_id', '=', 'produks.id')
        ->join('kategoris', 'produks.kategori_id', '=', 'kategoris.id')
        ->join('ukurans', 'varian_produks.ukuran_id', '=', 'ukurans.id')
        ->join('warnas', 'varian_produks.warna_id', '=', 'warnas.id');

    if ($tanggalMulai) {
        $detailQuery->whereDate(
            'transaksis.tanggal_transaksi', '>=', $tanggalMulai
        );
    }

    if ($tanggalAkhir) {
        $detailQuery->whereDate(
            'transaksis.tanggal_transaksi', '<=', $tanggalAkhir
        );
    }

    $produkTerjual = (clone $detailQuery)->sum('detail_transaksis.jumlah');

    $laporan = $detailQuery
        ->select(
            'transaksis.nomor_transaksi',
            'transaksis.tanggal_transaksi',
            'produks.nama_produk',
            'kategoris.nama_kategori',
            'ukurans.nama_ukuran',
            'warnas.nama_warna',
            'detail_transaksis.jumlah',
            'detail_transaksis.harga_satuan',
            'detail_transaksis.subtotal'
        )
        ->orderByDesc('transaksis.tanggal_transaksi')
        ->orderBy('transaksis.nomor_transaksi')
        ->get();

    $rataRataTransaksi = $jumlahTransaksi > 0
        ? $totalPendapatan / $jumlahTransaksi
        : 0;

    $pdf = Pdf::loadView('admin.laporan-penjualan.pdf', compact(
        'tanggalMulai',
        'tanggalAkhir',
        'totalPendapatan',
        'jumlahTransaksi',
        'produkTerjual',
        'rataRataTransaksi',
        'laporan'
    ))->setPaper('a4', 'landscape');

    return $pdf->download('laporan-penjualan.pdf');
}

    public function exportExcel(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        return Excel::download(
            new LaporanPenjualanExport(
                $validated['tanggal_mulai'] ?? null,
                $validated['tanggal_akhir'] ?? null
            ),
            'laporan-penjualan.xlsx'
        );
    }
}
