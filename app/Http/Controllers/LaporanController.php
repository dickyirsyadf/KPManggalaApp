<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    public function laporan(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $transaksi = Transaksi::with(['jenis_transaksi', 'detail_penjualans'])
            ->whereDate('tanggal_transaksi', '>=', $startDate)
            ->whereDate('tanggal_transaksi', '<=', $endDate)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $totalDebet = 0;
        $totalKredit = 0;
        $totalMargin = 0;

        // Lakukan kalkulasi total terlebih dahulu
        foreach ($transaksi as $item) {
            // Pemasukan: Penjualan (1) dan Kontrak Iklan (3)
            if (in_array($item->id_jenis_transaksi, [1, 3])) {
                $totalDebet += $item->nominal_transaksi;
                // Margin hanya dihitung dari penjualan
                if ($item->id_jenis_transaksi == 1 && $item->detail_penjualans) {
                    $totalMargin += $item->detail_penjualans->sum('margin');
                }
            }
            // Pengeluaran: Penggajian (2) dan Preorder (4)
            elseif (in_array($item->id_jenis_transaksi, [2, 4])) {
                $totalKredit += $item->nominal_transaksi;
            }
        }

        // Map data untuk ditampilkan di view
        $laporan = $transaksi->map(function ($item) {
            $debet = 0;
            $kredit = 0;
            $margin = 0;

            if (in_array($item->id_jenis_transaksi, [1, 3])) { // Pemasukan
                $debet = $item->nominal_transaksi;
                if ($item->id_jenis_transaksi == 1 && $item->detail_penjualans) {
                    $margin = $item->detail_penjualans->sum('margin');
                }
            } elseif (in_array($item->id_jenis_transaksi, [2, 4])) { // Pengeluaran
                $kredit = $item->nominal_transaksi;
            }

            return [
                'tanggal' => $item->tanggal_transaksi,
                'keterangan' => $item->jenis_transaksi->jenis_transaksi ?? 'N/A',
                'debet' => $debet,
                'kredit' => $kredit,
                'margin' => $margin,
                'total_bayar' => $debet, // Untuk modal detail
            ];
        });

        return view('admin.laporan_keuangan', [
            'laporan' => $laporan,
            'totalDebet' => $totalDebet,
            'totalKredit' => $totalKredit,
            'totalMargin' => $totalMargin,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function exportLaporan(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $transaksi = Transaksi::with(['jenis_transaksi', 'detail_penjualans'])
            ->whereDate('tanggal_transaksi', '>=', $startDate)
            ->whereDate('tanggal_transaksi', '<=', $endDate)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        $totalDebet = 0;
        $totalKredit = 0;
        $totalMargin = 0;

        $laporan = $transaksi->map(function ($item) use (&$totalDebet, &$totalKredit, &$totalMargin) {
            $debet = 0;
            $kredit = 0;

            if (in_array($item->id_jenis_transaksi, [1, 3])) { // Pemasukan
                $debet = $item->nominal_transaksi;
                if ($item->id_jenis_transaksi == 1 && $item->detail_penjualans) {
                    $totalMargin += $item->detail_penjualans->sum('margin');
                }
            } elseif (in_array($item->id_jenis_transaksi, [2, 4])) { // Pengeluaran
                $kredit = $item->nominal_transaksi;
            }

            $totalDebet += $debet;
            $totalKredit += $kredit;

            return [
                'tanggal' => $item->tanggal_transaksi,
                'keterangan' => $item->jenis_transaksi->jenis_transaksi ?? 'N/A',
                'debet' => $debet,
                'kredit' => $kredit,
            ];
        });

        $pdf = PDF::loadView('admin.laporan_keuangan_pdf', [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalDebet' => $totalDebet,
            'totalKredit' => $totalKredit,
            'totalMargin' => $totalMargin,
        ]);

        return $pdf->download('laporan-keuangan-' . $startDate . '-sampai-' . $endDate . '.pdf');
    }
}
