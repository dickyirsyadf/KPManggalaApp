<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Absensi;
class DashboardController extends Controller
{
    function dashboard()
    {
        $hariIni = Transaksi::getHariIni();
        $mingguIni = Transaksi::getMingguIni();
        $bulanIni = Transaksi::getBulanIni();

        $hasCheckedIn = Absensi::where('kehadiran', 1)
        ->whereDate('created_at', now()->toDateString())
        ->exists() ? 'yes' : 'no';

        $data = [
            'menu' => 'Dashboard',
        ];
        return view('admin.dashboard', compact('data','hasCheckedIn','hariIni','mingguIni','bulanIni'));
    }
}
