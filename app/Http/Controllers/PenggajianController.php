<?php

namespace App\Http\Controllers;

use App\Models\DaftarGaji;
use App\Models\Penggajian;
use App\Models\SlipGaji;
use App\Models\Transaksi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PenggajianController extends Controller
{
    // Definisikan tarif di sini agar mudah diubah
    const POTONGAN_ABSEN_PER_HARI = 50000;
    const POTONGAN_TELAT_PER_KALI = 25000;
    const PENDAPATAN_LEMBUR_PER_KALI = 30000;

    public function index()
    {
        $daftarGaji = DaftarGaji::all();
        $slipGajis = SlipGaji::orderBy('tanggal', 'desc')->get();
        return view('admin.penggajian', compact('daftarGaji', 'slipGajis'));
    }

    /**
     * Mengambil detail gaji untuk AJAX request.
     * Logika ini disempurnakan untuk menghitung semua komponen yang diperlukan.
     */
    public function getGaji($id_karyawan)
    {
        // Ambil data gaji berdasarkan id_karyawan
        $gaji = DaftarGaji::where('id_karyawan', $id_karyawan)->first();

        if (!$gaji) {
            return response()->json(['message' => 'Data gaji tidak ditemukan'], 404);
        }

        // Ambil data user untuk mendapatkan tunjangan dari jabatannya
        // Pastikan model User memiliki relasi 'jabatan'
        $user = User::with('jabatan')->find($id_karyawan);
        $tunjangan_jabatan = $user->jabatan->tunjangan_jabatan ?? 0;

        // Lakukan kalkulasi di sini
        $potongan_absen = $gaji->jml_absen * self::POTONGAN_ABSEN_PER_HARI;
        $potongan_telat = $gaji->jml_terlambat * self::POTONGAN_TELAT_PER_KALI;
        $total_potongan = $potongan_absen + $potongan_telat;

        $pendapatan_lembur = $gaji->jml_lembur * self::PENDAPATAN_LEMBUR_PER_KALI;
        $total_pendapatan = $gaji->gaji_pokok + $tunjangan_jabatan + $pendapatan_lembur;

        // Gaji bersih yang sudah dihitung oleh command
        $gaji_bersih_final = $gaji->gaji_bersih;

        // Kirim semua data yang diperlukan oleh view
        return response()->json([
            'jabatan' => $gaji->jabatan,
            'gaji_pokok' => $gaji->gaji_pokok,
            'potongan_absen' => $potongan_absen,
            'potongan_telat' => $potongan_telat,
            'total_potongan' => $total_potongan,
            'tunjangan_jabatan' => $tunjangan_jabatan,
            'pendapatan_lembur' => $pendapatan_lembur,
            'total_pendapatan' => $total_pendapatan,
            'gaji_bersih' => $gaji_bersih_final,
        ]);
    }

    /**
     * Menyimpan data penggajian.
     * Logika ini disederhanakan untuk mengambil data langsung dari request.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_karyawan' => 'required|exists:users,id',
                'jabatan' => 'required',
                'gaji_pokok' => 'required|numeric',
                'total_ptg' => 'required|numeric', // Menggunakan nama field dari form
                'gaji_bersih' => 'required|numeric',
                'total_pendapatan' => 'required|numeric',
            ]);

            $user = User::find($validated['id_karyawan']);

            // Buat entri di SlipGaji
            SlipGaji::create([
                'id_karyawan' => $validated['id_karyawan'],
                'nama' => $user->nama,
                'bagian' => $validated['jabatan'],
                'jumlah_hadir' => DaftarGaji::where('id_karyawan', $user->id)->value('jml_hadir') ?? 0,
                'tanggal' => now()->toDateString(),
                'penerimaan' => $validated['total_pendapatan'], // Menggunakan total pendapatan
                'potongan' => $validated['total_ptg'],
                'total' => $validated['gaji_bersih'],
            ]);

            // Buat entri di Transaksi
            // ... (Logika transaksi Anda) ...

            return redirect()->back()->with('success', 'Data Penggajian berhasil disimpan!');

        } catch (\Exception $e) {
            Log::error('Error in store method:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan! Periksa log untuk detail.');
        }
    }

    public function printSlip($id)
    {
        $slipGaji = SlipGaji::findOrFail($id);
        $pdf = Pdf::loadView('admin.slip-gaji', compact('slipGaji'));
        return $pdf->stream('slip-gaji-' . $slipGaji->id . '.pdf');
    }
}
