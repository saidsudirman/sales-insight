<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPenjualanExport implements FromCollection, WithHeadings
{
    protected $tanggalMulai;
    protected $tanggalAkhir;

    public function __construct($tanggalMulai = null, $tanggalAkhir = null)
    {
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalAkhir = $tanggalAkhir;
    }

    public function collection(): Collection
    {
        $query = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('varian_produks', 'detail_transaksis.varian_produk_id', '=', 'varian_produks.id')
            ->join('produks', 'varian_produks.produk_id', '=', 'produks.id')
            ->join('kategoris', 'produks.kategori_id', '=', 'kategoris.id')
            ->join('ukurans', 'varian_produks.ukuran_id', '=', 'ukurans.id')
            ->join('warnas', 'varian_produks.warna_id', '=', 'warnas.id');

        if ($this->tanggalMulai) {
            $query->whereDate(
                'transaksis.tanggal_transaksi',
                '>=',
                $this->tanggalMulai
            );
        }

        if ($this->tanggalAkhir) {
            $query->whereDate(
                'transaksis.tanggal_transaksi',
                '<=',
                $this->tanggalAkhir
            );
        }

        return $query
            ->select(
                'transaksis.tanggal_transaksi',
                'transaksis.nomor_transaksi',
                'produks.nama_produk',
                'kategoris.nama_kategori',
                'ukurans.nama_ukuran',
                'warnas.nama_warna',
                'detail_transaksis.jumlah',
                'detail_transaksis.harga_satuan',
                'detail_transaksis.subtotal'
            )
            ->orderByDesc('transaksis.tanggal_transaksi')
            ->get()
            ->map(function ($row) {
                return [
                    date('d-m-Y', strtotime($row->tanggal_transaksi)),
                    $row->nomor_transaksi,
                    $row->nama_produk,
                    $row->nama_kategori,
                    $row->nama_ukuran,
                    $row->nama_warna,
                    $row->jumlah,
                    $row->harga_satuan,
                    $row->subtotal,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nomor Transaksi',
            'Nama Produk',
            'Kategori',
            'Ukuran',
            'Warna',
            'Jumlah',
            'Harga Satuan',
            'Subtotal',
        ];
    }
}
