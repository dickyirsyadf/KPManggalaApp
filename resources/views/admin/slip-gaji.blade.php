<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .slip-container {
            border: 1px solid #000;
            padding: 20px;
            margin: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <div class="header">
            <h2>Slip Gaji</h2>
        </div>
        <div class="details">
            <p><strong>Nama:</strong> {{ $slipGaji->nama }}</p>
            <p><strong>Bagian:</strong> {{ $slipGaji->bagian }}</p>
            <p><strong>Jumlah Hadir:</strong> {{ $slipGaji->jumlah_hadir }}</p>
            <p><strong>Tanggal:</strong> {{ $slipGaji->tanggal }}</p>
            <p><strong>Penerimaan:</strong> Rp {{ number_format($slipGaji->penerimaan, 2, ',', '.') }}</p>
            <p><strong>Potongan:</strong> Rp {{ number_format($slipGaji->potongan, 2, ',', '.') }}</p>
            <p><strong>Total Gaji:</strong> Rp {{ number_format($slipGaji->total, 2, ',', '.') }}</p>
        </div>
    </div>
</body>
</html>
