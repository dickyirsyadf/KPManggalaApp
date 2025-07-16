<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $slipGaji->nama }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
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
        }
        .header p {
            margin: 5px 0;
        }
        .employee-details {
            width: 100%;
            margin-bottom: 20px;
        }
        .employee-details td {
            padding: 2px 5px;
        }
        .attendance-summary {
             width: 100%;
             margin-bottom: 20px;
             font-size: 11px;
             text-align: center;
        }
        .attendance-summary td {
            padding: 5px;
            border: 1px solid #ddd;
        }
        .earnings-deductions {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .earnings-deductions th, .earnings-deductions td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .earnings-deductions th {
            background-color: #f2f2f2;
        }
        .amount {
            text-align: right;
        }
        .net-pay {
            text-align: right;
            margin-top: 20px;
        }
        .net-pay h3 {
            margin: 0;
            font-size: 16px;
        }
        .signatures {
            margin-top: 60px;
            width: 100%;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid #999;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SLIP GAJI</h1>
            <p>PT. Manggala Tetap Jaya</p>
            <p>Periode: {{ $slipGaji->periode }}</p>
        </div>

        <table class="employee-details">
            <tr>
                <td style="width: 140px;"><strong>Nama Karyawan</strong></td>
                <td style="width: 10px;">:</td>
                <td>{{ $slipGaji->nama }}</td>
            </tr>
            <tr>
                <td><strong>Jabatan</strong></td>
                <td>:</td>
                <td>{{ $slipGaji->jabatan }}</td>
            </tr>
             <tr>
                <td><strong>Tanggal Pembayaran</strong></td>
                <td>:</td>
                <td>{{ now()->format('d F Y') }}</td>
            </tr>
        </table>

        <table class="attendance-summary">
             <tr>
                <td>Hadir: {{ $slipGaji->jumlah_hadir }}</td>
                <td>Sakit: {{ $slipGaji->jumlah_sakit }}</td>
                <td>Izin: {{ $slipGaji->jumlah_izin }}</td>
                <td>Absen: {{ $slipGaji->jumlah_absen }}</td>
                <td>Lembur: {{ $slipGaji->jumlah_lembur }}</td>
                <td>Terlambat: {{ $slipGaji->jumlah_terlambat }}</td>
            </tr>
        </table>

        <table class="earnings-deductions">
            <thead>
                <tr>
                    <th>PENDAPATAN</th>
                    <th class="amount">JUMLAH (Rp)</th>
                    <th>POTONGAN</th>
                    <th class="amount">JUMLAH (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gaji Pokok</td>
                    <td class="amount">{{ number_format($slipGaji->gaji_pokok, 0, ',', '.') }}</td>
                    <td>Potongan Absen</td>
                    <td class="amount">{{ number_format($slipGaji->potongan_absen, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Tunjangan Jabatan</td>
                    <td class="amount">{{ number_format($slipGaji->tunjangan_jabatan, 0, ',', '.') }}</td>
                    <td>Potongan Terlambat</td>
                    <td class="amount">{{ number_format($slipGaji->potongan_telat, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pendapatan Lembur</td>
                    <td class="amount">{{ number_format($slipGaji->pendapatan_lembur, 0, ',', '.') }}</td>
                    <td></td>
                    <td class="amount"></td>
                </tr>
                <tr>
                    <th>Total Pendapatan</th>
                    <th class="amount">{{ number_format($slipGaji->total_pendapatan, 0, ',', '.') }}</th>
                    <th>Total Potongan</th>
                    <th class="amount">{{ number_format($slipGaji->total_potongan, 0, ',', '.') }}</th>
                </tr>
            </tbody>
        </table>

        <div class="net-pay">
            <h3>GAJI BERSIH : Rp {{ number_format($slipGaji->gaji_bersih, 0, ',', '.') }}</h3>
        </div>

        <table class="signatures">
            <tr>
                <td>Disetujui oleh,</td>
                <td>Diterima oleh,</td>
            </tr>
            <tr>
                <td style="padding-top: 60px;">(HRD / Manager)</td>
                <td style="padding-top: 60px;">({{ $slipGaji->nama }})</td>
            </tr>
        </table>

        <div class="footer">
            Ini adalah slip gaji yang dibuat oleh komputer dan sah tanpa tanda tangan basah.
        </div>
    </div>
</body>
</html>
