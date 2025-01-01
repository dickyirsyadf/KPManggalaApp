<?php

namespace App\Http\Controllers;

use App\Models\DaftarGaji;
use App\Models\Penggajian;
use App\Models\SlipGaji;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\Absensi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class PenggajianController extends Controller
{
    public function index()
    {
        $daftarGaji = DaftarGaji::all();
        $penggajian = Penggajian::all();
        $slipGajis = SlipGaji::all(); // Include user data if needed
        return view('admin.penggajian', compact('daftarGaji', 'penggajian','slipGajis'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Request received:', $request->all());

            $validated = $request->validate([
                'id_karyawan' => 'required|exists:users,id',
                'bagian' => 'required',
                'jumlah_hadir' => 'required|numeric',
                'gaji_perhari' => 'required|numeric',
                'bonus' => 'required|numeric',
            ]);

            Log::info('Validation passed:', $validated);

            // Fetch DaftarGaji record
            $daftarGaji = DaftarGaji::where('id_karyawan', $request->id_karyawan)->first();
            if (!$daftarGaji) {
                Log::error('DaftarGaji not found for id_karyawan:', ['id_karyawan' => $request->id_karyawan]);
                return redirect()->back()->with('error', 'Daftar Gaji data not found!');
            }

            // Log calculated values
            $penerimaan = $validated['jumlah_hadir'] * $validated['gaji_perhari'];
            $daysInMonth = now()->daysInMonth;
            $potongan = ($daysInMonth - $validated['jumlah_hadir']) * 100000;
            $total = $penerimaan - $potongan;

            Log::info('Calculated values:', compact('penerimaan', 'daysInMonth', 'potongan', 'total'));

            // Post to Penggajian table
            Penggajian::create([
                'id' => $validated['id_karyawan'],
                'nama' => $daftarGaji->nama,
                'bagian' => $validated['bagian'],
                'tgl_terima_gaji' => now()->toDateString(),
            ]);

            Log::info('Penggajian record created.');

            // Post to SlipGaji table
            SlipGaji::create([
                'id' => $validated['id_karyawan'],
                'nama' => $daftarGaji->nama,
                'bagian' => $validated['bagian'],
                'jumlah_hadir' => $validated['jumlah_hadir'],
                'tanggal' => now()->toDateString(),
                'penerimaan' => $penerimaan,
                'potongan' => $potongan,
                'total' => $total,
            ]);

            Log::info('SlipGaji record created.');

            // Generate Transaction
            $lastTransaction = Transaksi::whereDate('tanggal_transaksi', now())
                ->orderBy('id', 'desc')
                ->first();
            $lastNumber = $lastTransaction ? intval(substr($lastTransaction->no_transaksi, -5)) : 0;
            $newTransactionNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

            Transaksi::create([
                'id_karyawan' => $validated['id_karyawan'],
                'no_transaksi' => 'TRS' . now()->format('dmY') . $newTransactionNumber,
                'id_jenis_transaksi' => 2,
                'nominal_transaksi' => $total,
                'tanggal_transaksi' => now()->toDateString(),
            ]);

            Log::info('Transaction record created.');

            return redirect()->back()->with('success', 'Data Penggajian berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error in store method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan! Periksa log untuk detail.');
        }
    }


    /**
     * Calculate jumlah_hadir for the previous month and forward to SlipGaji
     */
    public function generateSlipGaji()
    {
        $daftarGajiRecords = DaftarGaji::all();

        foreach ($daftarGajiRecords as $daftar) {
            $daysInMonth = now()->daysInMonth;
            $penerimaan = $daftar->jumlah_hadir * $daftar->gaji_perhari;
            $potongan = ($daysInMonth - $daftar->jumlah_hadir) * 100000;
            $total = $penerimaan - $potongan;

            SlipGaji::create([
                'id_karyawan' => $daftar->id_karyawan,
                'nama' => $daftar->nama,
                'bagian' => $daftar->bagian,
                'jumlah_hadir' => $daftar->jumlah_hadir,
                'tanggal' => now()->toDateString(),
                'penerimaan' => $penerimaan,
                'potongan' => $potongan,
                'total' => $total,
            ]);
        }

        return redirect()->back()->with('success', 'Slip Gaji generated successfully!');
    }

    public function getGaji($id)
    {
        // Fetch the gaji details based on id_karyawan
        $gaji = DaftarGaji::where('id_karyawan', $id)->first();

        // Check if the gaji exists
        if ($gaji) {
            return response()->json([
                'bagian' => $gaji->bagian,
                'jumlah_hadir' => $gaji->jumlah_hadir,
                'gaji_perhari' => $gaji->gaji_perhari,
                'bonus' => $gaji->bonus,
                'absen' => $gaji->absen,
                'gaji_bersih' => $gaji->gaji_bersih,
            ]);
        } else {
            return response()->json(['message' => 'Data not found'], 404);
        }
    }

    public function printSlip($id)
    {
        $slipGaji = SlipGaji::findOrFail($id);

        $pdf = PDF::loadView('admin.penggajian.slip-gaji', compact('slipGaji'));
        return $pdf->stream('slip-gaji.pdf');
    }
}
