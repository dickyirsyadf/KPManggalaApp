<?php

namespace App\Http\Controllers;

use App\Models\DaftarGaji;
use App\Models\Pengeluaran;
use App\Models\Penggajian; // <-- Tambahkan model Penggajian
use App\Models\Potongan;
use App\Models\SlipGaji;
use App\Models\Tunjangan;
use App\Models\Transaksi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PenggajianController extends Controller
{
    public function index()
    {
        // Mengambil hanya karyawan yang ada di daftar gaji untuk dropdown
        $daftarKaryawan = DaftarGaji::with('user')->get();
        $slipGajis = SlipGaji::orderBy('id', 'desc')->get(); // Mengurutkan berdasarkan ID terbaru
        return view('admin.penggajian', compact('daftarKaryawan', 'slipGajis'));
    }

    /**
     * Mengambil detail gaji untuk AJAX request dengan perhitungan dinamis.
     */
    public function getGaji($id_karyawan)
    {
        try {
            $gaji = DaftarGaji::where('id_karyawan', $id_karyawan)->firstOrFail();
            $user = User::with('jabatan')->findOrFail($id_karyawan);

            if (!$user->jabatan) {
                Log::warning("Karyawan ID: {$id_karyawan} tidak memiliki relasi jabatan.");
                throw new \Exception('Data jabatan untuk karyawan ini tidak ditemukan.');
            }

            $id_jabatan_karyawan = $user->jabatan->id;
            // Log untuk membantu debugging
            Log::info("Mencari tunjangan untuk Karyawan ID: {$id_karyawan} dengan Jabatan ID: {$id_jabatan_karyawan}");

            // Ambil data tunjangan berdasarkan ID dari relasi jabatan
            $tunjangan = Tunjangan::where('id_jabatan', $id_jabatan_karyawan)->first();

            if (!$tunjangan) {
                 Log::warning("Tunjangan tidak ditemukan di database untuk Jabatan ID: {$id_jabatan_karyawan}");
            }

            $tunjangan_jabatan = $tunjangan->tunjangan_jabatan ?? 0;
            $rate_lembur = $tunjangan->rate_lembur ?? 0;

            // Ambil data potongan
            $potongan = Potongan::first();
            $rate_potongan_absen = $potongan->potongan_absensi ?? 0;
            $rate_potongan_telat = $potongan->potongan_terlambat ?? 0;

            // --- Kalkulasi ---
            $potongan_absen = $gaji->jml_absen * $rate_potongan_absen;
            $potongan_telat = $gaji->jml_terlambat * $rate_potongan_telat;
            $total_potongan = $potongan_absen + $potongan_telat;

            $pendapatan_lembur = $gaji->jml_lembur * $rate_lembur;
            $total_pendapatan = $gaji->gaji_pokok + $tunjangan_jabatan + $pendapatan_lembur;

            $gaji_bersih_final = $total_pendapatan - $total_potongan;

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

        } catch (\Exception $e) {
            Log::error('Error getGaji: ' . $e->getMessage());
            return response()->json(['message' => 'Data tidak lengkap atau tidak ditemukan'], 404);
        }
    }

    /**
     * Menyimpan data penggajian, membuat slip gaji, dan mencatat transaksi keuangan.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'id_karyawan' => 'required|exists:users,id',
                'jabatan' => 'required',
                'gaji_pokok' => 'required|numeric',
                'tjg_jabatan' => 'required|numeric',
                'pendapatan_lembur' => 'required|numeric',
                'ptg_absen' => 'required|numeric',
                'ptg_telat' => 'required|numeric',
                'total_ptg' => 'required|numeric',
                'total_pendapatan' => 'required|numeric',
                'gaji_bersih' => 'required|numeric',
            ]);

            $user = User::find($validated['id_karyawan']);
            $daftarGaji = DaftarGaji::where('id_karyawan', $user->id)->first();
            $periodeGaji = now()->subMonth()->format('F Y');

            // 1. Membuat Slip Gaji
            SlipGaji::create([
                'id_karyawan' => $validated['id_karyawan'],
                'nama' => $user->nama,
                'jabatan' => $validated['jabatan'],
                'periode' => $periodeGaji,
                'gaji_pokok' => $validated['gaji_pokok'],
                'tunjangan_jabatan' => $validated['tjg_jabatan'],
                'pendapatan_lembur' => $validated['pendapatan_lembur'],
                'total_pendapatan' => $validated['total_pendapatan'],
                'potongan_absen' => $validated['ptg_absen'],
                'potongan_telat' => $validated['ptg_telat'],
                'total_potongan' => $validated['total_ptg'],
                'gaji_bersih' => $validated['gaji_bersih'],
                'jumlah_hadir' => $daftarGaji->jml_hadir ?? 0,
                'jumlah_sakit' => $daftarGaji->jml_sakit ?? 0,
                'jumlah_izin' => $daftarGaji->jml_izin ?? 0,
                'jumlah_absen' => $daftarGaji->jml_absen ?? 0,
                'jumlah_lembur' => $daftarGaji->jml_lembur ?? 0,
                'jumlah_terlambat' => $daftarGaji->jml_terlambat ?? 0,
            ]);

            // 2. Membuat Catatan Transaksi Keuangan
            Transaksi::create([
                'id_karyawan' => $validated['id_karyawan'],
                'no_transaksi' => 'GAJI-' . now()->format('Ymd') . '-' . mt_rand(1000, 9999),
                'id_jenis_transaksi' => 2,
                'nominal_transaksi' => $validated['gaji_bersih'],
                'tanggal_transaksi' => now(),
            ]);

            // 3. Membuat Catatan Pengeluaran
            Pengeluaran::create([
                'tanggal' => now(),
                'jumlah' => $validated['gaji_bersih'],
                'sumber_pengeluaran' => 'Kas Perusahaan',
                'keterangan' => 'Penggajian ' . $user->nama,
            ]);

            // 4. Membuat Catatan di Tabel Penggajian
            Penggajian::create([
                'id_penggajian' => 'PGJ-' . now()->format('Ymd') . '-' . mt_rand(1000, 9999),
                'id_karyawan' => $validated['id_karyawan'],
                'nama' => $user->nama,
                'jabatan' => $validated['jabatan'],
                'periode_gaji' => $periodeGaji,
                'tgl_terima_gaji' => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Data Penggajian berhasil disimpan dan semua transaksi terkait telah dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in store penggajian:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan! Periksa log untuk detail.');
        }
    }

    public function printSlip($id)
    {
        $slipGaji = SlipGaji::findOrFail($id);
        $pdf = Pdf::loadView('admin.slip-gaji', compact('slipGaji'));
        return $pdf->stream('slip-gaji-' . $slipGaji->nama . '-' . $slipGaji->id . '.pdf');
    }
}
