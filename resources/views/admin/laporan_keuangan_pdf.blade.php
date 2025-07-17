<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 12px;
        }
        @page {
            margin: 40px 50px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .summary-table {
            width: 100%;
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
        }
        .summary-table .label {
            font-weight: bold;
            background-color: #f9f9f9;
            width: 30%;
        }
        .summary-table .value {
            text-align: right;
        }
        .summary-table .value.debet {
            color: #27ae60;
            font-weight: bold;
        }
        .summary-table .value.kredit {
            color: #c0392b;
            font-weight: bold;
        }
        .summary-table .value.profit {
            color: #2980b9;
            font-weight: bold;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th, .main-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .main-table td.text-right {
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .page-number:before {
            content: "Halaman " counter(page);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>PT. Manggala Tetap Jaya</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} &mdash; {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>
    </div>

    <h3>Ringkasan Laporan</h3>
    <table class="summary-table">
        <tr>
            <td class="label">Total Pemasukan (Debet)</td>
            <td class="value debet">Rp {{ number_format($totalDebet, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Total Pengeluaran (Kredit)</td>
            <td class="value kredit">Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Keuntungan Bersih (Margin)</td>
            <td class="value profit">Rp {{ number_format($totalMargin, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h3>Rincian Transaksi</h3>
    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th>Keterangan</th>
                <th style="width: 20%;">Debet</th>
                <th style="width: 20%;">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($laporan as $item)
                <tr>
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                    <td class="text-right">{{ $item['debet'] > 0 ? 'Rp ' . number_format($item['debet'], 0, ',', '.') : '-' }}</td>
                    <td class="text-right">{{ $item['kredit'] > 0 ? 'Rp ' . number_format($item['kredit'], 0, ',', '.') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem pada tanggal {{ now()->isoFormat('D MMMM Y, HH:mm:ss') }}.
        <div class="page-number"></div>
    </div>
</body>
</html>
