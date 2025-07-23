<?php

namespace App\Http\Controllers;

use App\Models\DaftarGaji;
use App\Models\Jabatan;
use App\Models\HakAkses;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class DaftarGajiController extends Controller
{
    /**
     * Menampilkan halaman daftar gaji.
     */
    public function index()
    {
        $karyawans = User::with('hakakses','jabatan')->get(); // Diperlukan untuk dropdown "Tambah"
        $menu = 'Daftar Gaji';
        return view('admin.daftargaji', compact('karyawans', 'menu'));
    }


    /**
     * Menyediakan data untuk DataTables.
     */
    public function data()
    {
        // Menggunakan Eloquent untuk query yang lebih bersih
        $data = DaftarGaji::query();
        return DataTables::of($data)->toJson();
    }


    /**
     * Menyimpan data gaji awal.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_karyawan' => 'required|exists:users,id|unique:daftar_gaji,id_karyawan',
                'nama' => 'required|string',
                'jabatan' => 'required|string',
                'gaji_pokok' => 'required|integer|min:0',
            ]);

            // Membuat record gaji awal. Kolom lain akan diisi oleh command.
            DaftarGaji::create([
                'id_karyawan' => $request->id_karyawan,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'gaji_pokok' => $request->gaji_pokok,
                'jml_hr_kerja' => 0, // Akan diisi oleh command
                'jml_hadir' => 0,
                'jml_absen' => 0,
                'jml_izin' => 0,
                'jml_sakit' => 0,
                'jml_terlambat' => 0,
                'jml_lembur' => 0,
            ]);
            return back()->with('success', 'Tambah Gaji Berhasil');
        } catch (Exception $e) {
            Log::error('Error storing Gaji: ' . $e->getMessage());
            return back()->with('error', 'Tambah Gaji Gagal! Pastikan karyawan belum ada di daftar.');
        }
    }

    /**
     * Update data gaji yang sudah ada.
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'id_karyawan' => 'required|exists:daftar_gaji,id_karyawan', // Validasi berdasarkan id_karyawan
                'gaji_pokok' => 'required|integer|min:0',
            ]);

            // Cari record berdasarkan id_karyawan, bukan primary key 'id'
            $gaji = DaftarGaji::where('id_karyawan', $request->id_karyawan)->firstOrFail();

            // PERBAIKAN: Hapus pembaruan pada kolom 'gaji_bersih' yang tidak ada
            $gaji->update([
                'gaji_pokok' => $request->gaji_pokok,
            ]);

            return back()->with('success', 'Edit Gaji Berhasil');
        } catch (Exception $e) {
            Log::error('Error updating Gaji: ' . $e->getMessage());
            return back()->with('error', 'Edit Gaji Gagal!');
        }
    }


    /**
     * Menghapus data gaji.
     * @param string $id Ini adalah id_karyawan dari route
     */
    public function destroy($id)
    {
        try {
            // Menemukan record berdasarkan id_karyawan dan menghapusnya
            $gaji = DaftarGaji::where('id_karyawan', $id)->firstOrFail();
            $gaji->delete();

            return back()->with('success', 'Hapus Daftar Gaji Berhasil!');
        } catch (Exception $e) {
            Log::error("Error deleting Daftar Gaji: " . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data!');
        }
    }
}
