<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Set zona waktu ke Asia/Jakarta
        Carbon::setLocale('id');
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 1. Data Ringkasan Keuangan Bulan Ini
        $pemasukanBulanIni = Pemasukan::whereBetween('tanggal', [$startOfMonth, $today])->sum('jumlah');
        $pengeluaranBulanIni = Pengeluaran::whereBetween('tanggal', [$startOfMonth, $today])->sum('jumlah');
        $keuntunganBulanIni = DetailPenjualan::whereHas('penjualan', function ($query) use ($startOfMonth, $today) {
            $query->whereBetween('tgl_penjualan', [$startOfMonth, $today]);
        })->sum('margin');


        // 2. Data untuk Grafik Penjualan 7 Hari Terakhir
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayName = $date->isoFormat('dddd'); // Format hari dalam Bahasa Indonesia
            $totalSales = Penjualan::whereDate('tgl_penjualan', $date)->sum('total_bayar');
            $salesData['labels'][] = $dayName;
            $salesData['data'][] = $totalSales;
        }

        // 3. Produk Terlaris Bulan Ini
        $produkTerlaris = DetailPenjualan::select('id_barang', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('penjualan', function ($query) use ($startOfMonth, $today) {
                $query->whereBetween('tgl_penjualan', [$startOfMonth, $today]);
            })
            ->with('barang') // Eager load data barang
            ->groupBy('id_barang')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        // 4. Status Absensi Dinamis
        $absensiHariIni = Absensi::where('id_karyawan', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        $absensiStatus = 'belum_absen'; // Default status
        if ($absensiHariIni) {
            if ($absensiHariIni->jam_keluar) {
                $absensiStatus = 'sudah_pulang';
            } else {
                $absensiStatus = 'sudah_masuk';
            }
        }

        $data = [
            'menu' => 'Dashboard',
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'keuntunganBulanIni' => $keuntunganBulanIni,
            'salesData' => $salesData,
            'produkTerlaris' => $produkTerlaris,
            'absensiStatus' => $absensiStatus,
            'absensiHariIni' => $absensiHariIni,
        ];

        return view('admin.dashboard', $data);
    }
}
