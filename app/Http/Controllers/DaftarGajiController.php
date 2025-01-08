<?php

namespace App\Http\Controllers;

use App\Models\DaftarGaji;
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
     * Display the daftar gaji view.
     */
    public function index()
    {
        $karyawans = User::with('hakakses')->get(); // Load users with their related hakakses
        $hakakses = HakAkses::all(); // Fetch all hakakses
        $menu = 'Daftar Gaji'; // Define menu

        // Pass all variables to the view
        return view('admin.daftargaji', compact('karyawans', 'hakakses', 'menu'));
    }


    /**
     * Fetch data for DataTable.
     */
    public function data()
{
    $data = DB::table('daftar_gaji')
        ->join('users as karyawan', 'daftar_gaji.id_karyawan', '=', 'karyawan.id')
        ->join('hakakses', 'karyawan.id_hakakses', '=', 'hakakses.id') // Join the hakakses table
        ->select(
            'daftar_gaji.*',
            'karyawan.nama',
            'hakakses.hakakses' // Use bagian from hakakses
        )
        ->get();

    // Return the data in the DataTables format
    return DataTables::of($data)->toJson();
}


    /**
     * Store a new gaji record.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'id_karyawan' => 'required|exists:users,id',
                'nama'=>'required',
                'jumlah_hadir' => 'nullable|integer|min:0',
                'gaji_perhari' => 'required|integer|min:0',
                'absen' => 'nullable|integer|min:0',
                'bonus' => 'required|integer|min:0',
                'gaji_bersih' => 'required|integer|min:0',
            ]);

            $user = User::with('hakAkses')->findOrFail($request->id_karyawan);
            $bagian = $user->hakAkses->hakakses;

            DaftarGaji::create([
                'id_karyawan' => $request->id_karyawan,
                'nama'=> $request->nama,
                'bagian' => $bagian,
                'jumlah_hadir' => $request->jumlah_hadir ?? null,
                'gaji_perhari' => $request->gaji_perhari,
                'absen' => $request->absen ?? null,
                'bonus' => $request->bonus,
                'gaji_bersih' => $request->gaji_bersih,
            ]);
            return back()->with('success', 'Tambah Gaji Berhasil');
        }catch(Exception $e){
            return back()->with('error' . $e->getMessage() , 'Tambah barang Gagal! Isi Form Dengan Benar');
        }

    }


    /**
     * Fetch details for a specific gaji record.
     */
    public function getGaji($id)
    {
        $gaji = DaftarGaji::with('user')->findOrFail($id);
        return response()->json($gaji);
    }

    /**
     * Update an existing gaji record.
     */
    public function update(Request $request)
    {
        try {
            // Log the request payload for debugging
            Log::info('Update request received', ['payload' => $request->all()]);

            // Validate the request
            $request->validate([
                'id_karyawan' => 'required|exists:users,id',
                'gaji_perhari' => 'required|integer|min:0',
            ]);

            // Fetch the DaftarGaji record
            $gaji = DaftarGaji::where('id_karyawan', $request->id_karyawan)->first();

            // Log if the record is not found
            if (!$gaji) {
                Log::warning('DaftarGaji record not found', ['id_karyawan' => $request->id_karyawan]);
                return response()->json(['message' => 'Record not found'], 404);
            }

            // Log the record before updating
            Log::info('DaftarGaji record before update', ['record' => $gaji]);

            // Perform the update
            $gaji->update([
                'gaji_perhari' => $request->gaji_perhari,
                'gaji_bersih' => 0,
            ]);

            // Log the updated record
            Log::info('DaftarGaji record updated successfully', ['updated_record' => $gaji->fresh()]);

            return back()->with('success', 'Edit Gaji Berhasil');
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error updating DaftarGaji record', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('Gagal', 'Edit Gaji Gagal');
        }
    }




    /**
     * Delete a gaji record.
     */
    public function destroy($id)
    {
        try{
            $gaji = DaftarGaji::findOrFail($id);
            $gaji->delete();

            // Return a success response
            return back()->with('success', 'Hapus Daftar Gaji Berhasil !');
        }catch(Exception $e){
            return back()->with('error', 'Hapus Daftar Gaji Gagal! Data Tidak Ditemukan');
        }
    }
}
