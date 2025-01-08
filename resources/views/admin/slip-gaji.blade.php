<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
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
        .details {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
        }
        .details p {
            margin: 5px 0;
            line-height: 1.6;
            font-size: 14px;
        }
        .details p strong {
            color: #333;
        }
        .totals {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
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
            <h1>Slip Gaji</h1>
            <p>PT. Manggala Tetap Jaya</p>
            <p>{{ \Carbon\Carbon::parse($slipGaji->tanggal)->format('F Y') }}</p>
        </div>

        <!-- Employee Details -->
        <div class="details">
            <p><strong>Nama:</strong> {{ $slipGaji->nama }}</p>
            <p><strong>Bagian:</strong> {{ $slipGaji->bagian }}</p>
            <p><strong>Jumlah Hadir:</strong> {{ $slipGaji->jumlah_hadir }}</p>
            <p><strong>Tanggal:</strong> {{ $slipGaji->tanggal }}</p>
            <p><strong>Penerimaan:</strong> Rp {{ number_format($slipGaji->penerimaan, 2, ',', '.') }}</p>
            <p><strong>Potongan:</strong> Rp {{ number_format($slipGaji->potongan, 2, ',', '.') }}</p>
            <p><strong>Total Gaji:</strong> Rp {{ number_format($slipGaji->total, 2, ',', '.') }}</p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Slip gaji ini diterbitkan oleh PT. Manggala Tetap Jaya.</p>
            <p>&copy; {{ date('Y') }} PT. Manggala Tetap Jaya. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
