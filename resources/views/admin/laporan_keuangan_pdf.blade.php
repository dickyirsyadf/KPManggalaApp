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
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Keuangan</h2>
    <p style="text-align: center;">Periode:
        {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}
        sampai
        {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
    </p>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th>Debet (Masuk)</th>
                <th>Kredit (Keluar)</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $saldo = 0;
            @endphp
            @foreach($laporan as $item)
                @php
                    $debet = $item['debet'] ?? 0;
                    $kredit = $item['kredit'] ?? 0;
                    $saldo += $debet - abs($kredit);
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($debet, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format(abs($kredit), 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($saldo, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
