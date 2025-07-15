<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .header p {
            font-size: 14px;
            margin: 5px 0;
            color: #555;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .details {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .details p {
            margin: 5px 0;
            line-height: 1.6;
            font-size: 14px;
        }
        .details p strong {
            color: #333;
        }
        .signatures {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .signature-block {
            text-align: center;
            width: 45%;
        }
        .signature-block p {
            margin: 5px 0;
            font-size: 14px;
        }
        .signature-space {
            height: 50px;
            margin-top: 10px;
            border-bottom: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        .date {
            text-align: center;
            font-size: 14px;
            margin-top: 5px;
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>Laporan Keuangan</h1>
            <p>PT. Manggala Tetap Jaya</p>
            <p>Periode:
                {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}
                sampai
                {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
            </p>
        </div>

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

        <!-- Signature Section -->
        <div class="signatures">
            <!-- Disetujui -->
            <div class="signature-block">
                <p><strong>Disetujui</strong></p>
                <p>(Manager Keuangan)</p>
                <div class="signature-space"></div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Laporan ini diterbitkan oleh PT. Manggala Tetap Jaya.</p>
            <p>&copy; {{ date('Y') }} PT. Manggala Tetap Jaya. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
