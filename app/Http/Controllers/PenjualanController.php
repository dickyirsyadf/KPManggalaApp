<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenjualanController extends Controller
{
   function index(){
    $data = [
        'menu' => 'Penjualan',
    ];

    return view('admin.penjualan', $data);
   }
}
