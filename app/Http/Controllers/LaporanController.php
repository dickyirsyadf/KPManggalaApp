<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;




class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function laporan(Request $request)
     {
         $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
         $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

         $transaksi = Transaksi::with(['jenis_transaksi', 'detail_penjualans'])
             ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
             ->orderBy('tanggal_transaksi')
             ->get();

         // Log transactions for debugging
         Log::info('Transaksi Retrieved:', $transaksi->toArray());

         $laporan = $transaksi->map(function ($item) {
            $margin = $item->detail_penjualans->sum('margin'); // Sum margin from detail_penjualans
            $totalBayar = $item->nominal_transaksi; // Use nominal_transaksi directly

            Log::info('Detail Penjualans for Transaction ID ' . $item->id, $item->detail_penjualans->toArray());
            Log::info('Calculated Margin for Transaction ID ' . $item->id . ': ' . $margin);

            return [
                'id' => $item->id,
                'tanggal' => $item->tanggal_transaksi,
                'keterangan' => $item->jenis_transaksi->jenis_transaksi ?? 'N/A',
                'debet' => $item->id_jenis_transaksi == 1 ? $item->nominal_transaksi : 0,
                'kredit' => $item->id_jenis_transaksi == 2 ? $item->nominal_transaksi : 0,
                'margin' => $margin, // This should now be correct
                'total_bayar' => $totalBayar,
            ];
        });


         // Log final laporan data
         Log::info('Generated Laporan Data:', $laporan->toArray());

         return view('admin.laporan_keuangan', [
             'laporan' => $laporan,
             'startDate' => $startDate,
             'endDate' => $endDate,
         ]);
     }


     public function exportLaporan(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // Retrieve transactions within the date range and eager load related models
        $transaksi = Transaksi::with('detail_penjualans')
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('tanggal_transaksi')
            ->get();

        // Initialize totals for debet and margin
        $totalDebet = 0;
        $totalMargin = 0;

        // Prepare laporan data with margin sums, debet sums, and total calculations
        $laporan = $transaksi->map(function ($item) use (&$totalDebet, &$totalMargin) {
            // Calculate margin for this transaction by summing detail_penjualans' margin
            $margin = $item->detail_penjualans->sum('margin');
            $debet = $item->id_jenis_transaksi == 1 ? $item->nominal_transaksi : 0;

            // Add to the totals
            $totalDebet += $debet;
            $totalMargin += $margin;

            return [
                'tanggal' => $item->tanggal_transaksi,
                'keterangan' => $item->jenis_transaksi->jenis_transaksi ?? 'N/A',
                'debet' => $debet,
                'kredit' => $item->id_jenis_transaksi == 2 ? $item->nominal_transaksi : 0,
                'margin' => $margin,
                'total_bayar' => $item->nominal_transaksi,
            ];
        });

        // Pass the laporan data and totals to the PDF view
        $pdf = PDF::loadView('admin.laporan_keuangan_pdf', [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalDebet' => $totalDebet,
            'totalMargin' => $totalMargin,
        ]);

        return $pdf->download('laporan_keuangan.pdf');
    }




}
