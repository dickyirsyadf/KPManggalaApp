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
            @foreach($transactions as $transaction)
                @php
                    $debit = $transaction->type === 'debit' ? $transaction->amount : 0;
                    $credit = $transaction->type === 'credit' ? $transaction->amount : 0;
                    $saldo += $debit - $credit;
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($debit, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($credit, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($saldo, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
