
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analitik Penjualan</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
            color: #1E293B;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }

        .info {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #1E293B;
            color: white;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-bottom: 15px;
        }

        .summary td {
            border: none;
            padding: 4px;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #777;
        }
    </style>
</head>
<body>

    <h2>LAPORAN ANALITIK PENJUALAN</h2>
    <div class="subtitle">UMKM MIYA</div>

    <div class="info">
        <strong>Periode:</strong>
        {{ $tanggalMulai ? date('d-m-Y', strtotime($tanggalMulai)) : 'Semua tanggal' }}
        -
        {{ $tanggalAkhir ? date('d-m-Y', strtotime($tanggalAkhir)) : 'Semua tanggal' }}
        <br>
        <strong>Tanggal Cetak:</strong> {{ date('d-m-Y H:i') }}
    </div>

    <table class="summary">
        <tr>
            <td><strong>Total Pendapatan</strong></td>
            <td>: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            <td><strong>Jumlah Transaksi</strong></td>
            <td>: {{ $jumlahTransaksi }}</td>
        </tr>
        <tr>
            <td><strong>Produk Terjual</strong></td>
            <td>: {{ $produkTerjual }}</td>
            <td><strong>Rata-rata Transaksi</strong></td>
            <td>: Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>No. Transaksi</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Ukuran</th>
                <th>Warna</th>
                <th class="text-center">Jumlah</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($laporan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($item->tanggal_transaksi)) }}</td>
                    <td>{{ $item->nomor_transaksi }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->nama_kategori }}</td>
                    <td>{{ $item->nama_ukuran }}</td>
                    <td>{{ $item->nama_warna }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-right">
                        {{ number_format($item->harga_satuan, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">
                        Tidak ada data penjualan pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak dari MIYA Sales Insight
    </div>

</body>
</html>
