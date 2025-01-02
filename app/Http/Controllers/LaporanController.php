<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function laporan(Request $request)
     {
         $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
         $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

         // Fetch transactions with date filtering
         $transaksi = Transaksi::with('jenis_transaksi')
             ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
             ->orderBy('tanggal_transaksi')
             ->get();

         // Group transactions by date and calculate totals
         $laporan = $transaksi->groupBy('tanggal_transaksi')->map(function ($items, $tanggal) {
             $debet = $items->where('id_jenis_transaksi', 1)->sum('nominal_transaksi');
             $kredit = $items->where('id_jenis_transaksi', 2)->sum('nominal_transaksi');
             $jenisTransaksi = $items->map(function ($item) {
                 return $item->jenis_transaksi->jenis_transaksi; // Adjust field name if needed
             })->unique()->join(', ');

             return [
                 'tanggal' => $tanggal,
                 'keterangan' => $jenisTransaksi,
                 'debet' => $debet,
                 'kredit' => $kredit,
                 'saldo' => $debet - $kredit,
             ];
         });

         // Pass data to the view
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

         $transaksi = Transaksi::with('jenisTransaksi')
             ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
             ->orderBy('tanggal_transaksi')
             ->get();

         // Group and calculate laporan
         $laporan = $transaksi->groupBy('tanggal_transaksi')->map(function ($items, $tanggal) {
             $debet = $items->where('id_jenis_transaksi', 1)->sum('nominal_transaksi');
             $kredit = $items->where('id_jenis_transaksi', 2)->sum('nominal_transaksi');
             $jenisTransaksi = $items->map(function ($item) {
                 return $item->jenisTransaksi->nama_jenis;
             })->unique()->join(', ');

             return [
                 'tanggal' => $tanggal,
                 'keterangan' => $jenisTransaksi,
                 'debet' => $debet,
                 'kredit' => $kredit,
                 'saldo' => $debet - $kredit,
             ];
         });

         // Generate PDF
         $pdf = PDF::loadView('admin.laporan_keuangan_pdf', ['laporan' => $laporan]);
         return $pdf->download('laporan_keuangan.pdf');
     }

}
