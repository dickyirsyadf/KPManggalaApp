<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    function dashboard()
    {
        // $transactions = Transaksi::all();
        $data = [
            'menu' => 'Dashboard',
            // 'transactions' => $transactions,
        ];
        return view('superadmin.dashboard', $data);
    }
}
