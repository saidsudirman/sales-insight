<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Validasi filter tanggal
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

        // Fungsi untuk menerapkan filter periode
        $filterPeriode = function ($query, $kolom = 'transaksis.tanggal_transaksi') use (
            $tanggalMulai,
            $tanggalAkhir
        ) {
            if ($tanggalMulai) {
                $query->whereDate($kolom, '>=', $tanggalMulai);
            }

            if ($tanggalAkhir) {
                $query->whereDate($kolom, '<=', $tanggalAkhir);
            }

            return $query;
        };

        // KPI
        $queryTransaksi = $filterPeriode(Transaksi::query());

        $totalPendapatan = (clone $queryTransaksi)->sum('grand_total');
        $jumlahTransaksi = (clone $queryTransaksi)->count();

        $produkTerjual = $filterPeriode(
            DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
        )->sum('detail_transaksis.jumlah');

        $rataRataTransaksi = $jumlahTransaksi > 0
            ? $totalPendapatan / $jumlahTransaksi
            : 0;

        // Data master
        $totalProduk = Produk::count();
        $totalKategori = Kategori::count();
        $totalPelanggan = Pelanggan::count();

        // Grafik tren penjualan
        $trenPenjualan = $filterPeriode(
            Transaksi::query()
        )
            ->selectRaw('DATE(tanggal_transaksi) as tanggal')
            ->selectRaw('SUM(grand_total) as total_pendapatan')
            ->groupByRaw('DATE(tanggal_transaksi)')
            ->orderBy('tanggal')
            ->get();

        $labelGrafik = $trenPenjualan->pluck('tanggal');
        $dataGrafik = $trenPenjualan->pluck('total_pendapatan');

        // Produk terlaris
        $produkTerlaris = $filterPeriode(
            DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->join('varian_produks', 'detail_transaksis.varian_produk_id', '=', 'varian_produks.id')
                ->join('produks', 'varian_produks.produk_id', '=', 'produks.id')
        )
            ->select(
                'produks.nama_produk',
                DB::raw('SUM(detail_transaksis.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksis.subtotal) as total_pendapatan')
            )
            ->groupBy('produks.id', 'produks.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Penjualan berdasarkan kategori
        $penjualanKategori = $filterPeriode(
            DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->join('varian_produks', 'detail_transaksis.varian_produk_id', '=', 'varian_produks.id')
                ->join('produks', 'varian_produks.produk_id', '=', 'produks.id')
                ->join('kategoris', 'produks.kategori_id', '=', 'kategoris.id')
        )
            ->select(
                'kategoris.nama_kategori',
                DB::raw('SUM(detail_transaksis.jumlah) as total_terjual')
            )
            ->groupBy('kategoris.id', 'kategoris.nama_kategori')
            ->orderByDesc('total_terjual')
            ->get();

        // Penjualan berdasarkan ukuran
        $penjualanUkuran = $filterPeriode(
            DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->join('varian_produks', 'detail_transaksis.varian_produk_id', '=', 'varian_produks.id')
                ->join('ukurans', 'varian_produks.ukuran_id', '=', 'ukurans.id')
        )
            ->select(
                'ukurans.nama_ukuran',
                DB::raw('SUM(detail_transaksis.jumlah) as total_terjual')
            )
            ->groupBy('ukurans.id', 'ukurans.nama_ukuran')
            ->orderByDesc('total_terjual')
            ->get();

        // Penjualan berdasarkan warna
        $penjualanWarna = $filterPeriode(
            DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->join('varian_produks', 'detail_transaksis.varian_produk_id', '=', 'varian_produks.id')
                ->join('warnas', 'varian_produks.warna_id', '=', 'warnas.id')
        )
            ->select(
                'warnas.nama_warna',
                DB::raw('SUM(detail_transaksis.jumlah) as total_terjual')
            )
            ->groupBy('warnas.id', 'warnas.nama_warna')
            ->orderByDesc('total_terjual')
            ->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalKategori',
            'totalPelanggan',
            'jumlahTransaksi',
            'totalPendapatan',
            'produkTerjual',
            'rataRataTransaksi',
            'labelGrafik',
            'dataGrafik',
            'produkTerlaris',
            'penjualanKategori',
            'penjualanUkuran',
            'penjualanWarna',
            'tanggalMulai',
            'tanggalAkhir'
        ));
    }
}
