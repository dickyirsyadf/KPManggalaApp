<?php

namespace App\Http\Controllers;

use App\Models\DaftarGaji;
use App\Models\Penggajian;
use App\Models\SlipGaji;
use Illuminate\Http\Request;
use PDF; // Pastikan dompdf telah diinstal via composer require barryvdh/laravel-dompdf

class PenggajianController extends Controller
{
    public function index()
    {
        $daftarGaji = DaftarGaji::all();
        $penggajian = Penggajian::all();

        return view('admin.penggajian', compact('daftarGaji', 'penggajian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'bagian' => 'required',
            'jumlah_hadir' => 'required|integer',
            'gaji_perhari' => 'required|integer',
            'absen' => 'required|integer',
            'bonus' => 'required|integer',
            'gaji_bersih' => 'required|integer',
        ]);

        Penggajian::create($request->all());
        return redirect()->route('admin.penggajian')->with('success', 'Data gaji berhasil disimpan!');
    }
    public function getGaji($id)
    {
        // Fetch the gaji details based on user_id
        $gaji = DaftarGaji::where('id_karyawan', $id)->first();

        // Check if the gaji exists
        if ($gaji) {
            return response()->json([
                'bagian' => $gaji->bagian,
                'jumlah_hadir' => $gaji->jumlah_hadir,
                'gaji_perhari' => $gaji->gaji_perhari,
                'bonus' => $gaji->bonus,
                'absen' => $gaji->absen,
                'gaji_bersih' => $gaji->gaji_bersih
            ]);
        } else {
            return response()->json(['message' => 'Data not found'], 404);
        }
    }

    public function printSlip($id)
    {
        $slipGaji = DaftarGaji::findOrFail($id);

        $pdf = PDF::loadView('admin.penggajian.slip-gaji', compact('slipGaji'));
        return $pdf->stream('slip-gaji.pdf');
    }
}
