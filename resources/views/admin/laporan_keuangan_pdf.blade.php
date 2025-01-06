<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Keuangan</h2>
    <p style="text-align: center;">Periode:
        {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}
        sampai
        {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
    </p>
    <!-- Table Content -->
    <!-- Table Content -->
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Debet (Masuk)</th>
                <th>Kredit (Keluar)</th>
                <th>Saldo</th>
                <th>Margin</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $saldo = 0;
            @endphp
            @foreach($laporan as $item)
                @php
                    $saldo += $item['debet'] - $item['kredit'];
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                    <td>Rp {{ number_format($item['debet'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['kredit'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($saldo, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['margin'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <!-- Merge rows for Total Penjualan and Total Keuntungan -->
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total Penjualan</strong></td>
                <td colspan="2" style="text-align: center;">Rp {{ number_format($totalDebet, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total Keuntungan</strong></td>
                <td colspan="2" style="text-align: center;"></td> <!-- Empty cells for the Debet and Kredit columns -->
                <td colspan="2" style="text-align: center;">Rp {{ number_format($totalMargin, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Other content for the report -->

</body>
</html>
