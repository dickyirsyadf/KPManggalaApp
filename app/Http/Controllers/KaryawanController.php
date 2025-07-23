<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\HakAkses;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Log;

class KaryawanController extends Controller
{
    function index(){
        $hakakses = HakAkses::all();
        $jabatan = Jabatan::all();
        $data = [
            'menu' => 'Karyawan',
            'hakakses' => $hakakses,
            'jabatan' => $jabatan,
        ];
        return view('admin.karyawan', $data );
    }

    function karyawan()
    {
        // PERBAIKAN: Cukup muat relasi dengan 'with'.
        // DataTables akan secara otomatis menangani relasi bersarang
        // jika dipanggil dengan benar di sisi view (JavaScript).
        $users = User::with(['hakakses', 'jabatan']);

        return DataTables::of($users)->make(true);
    }

    public function getHakAksesById($id)
    {
        // Retrieve HakAkses by ID
        $hakakses = HakAkses::find($id);

        if ($hakakses) {
            return response()->json(['hakakses' => $hakakses->hakakses]);  // Assuming 'hakakses' is the name column
        }

        return response()->json(['error' => 'HakAkses not found'], 404);
    }
    public function getJabatanById($id)
    {
        // Retrieve HakAkses by ID
        $jabatan = Jabatan::find($id);

        if ($jabatan) {
            return response()->json(['jabatan' => $jabatan->jabatan]);  // Assuming 'hakakses' is the name column
        }

        return response()->json(['error' => 'Jabatan not found'], 404);
    }

    function update(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'id_karyawan' => 'required|exists:users,id',
                'nama_karyawan' => 'required|string|max:255',
                'femail' => 'required|string|max:255',
                'fhakakses' => 'required|numeric',
                'fjabatan' => 'required',
                'fno_hp' => 'required|numeric',
            ]);

            // Find the record by ID
            $User = User::findOrFail($request->id_karyawan);

            // Update the record
            $User->nama = $request->nama_karyawan;
            $User->email = $request->femail;
            $User->id_hakakses = $request->fhakakses;
            $User->id_jabatan = $request->fjabatan;
            $User->no_hp = $request->fno_hp;
            $User->save();

            return back()->with('success', 'Edit User Berhasil !');

        } catch (\Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add user. Please try again.');
        }
    }

    function delete($id)
    {
        try{
            $User = User::findOrFail($id);
            // Delete the item
            $User->delete();

            // Return a success response
            return back()->with('success', 'Hapus User Berhasil !');
        }catch(Exception $e){
            return back()->with('error', 'Hapus User Gagal! Data Tidak Ditemukan');
        }

    }
}
