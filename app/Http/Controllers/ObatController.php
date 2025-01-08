<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class ObatController extends Controller
{
    function index(){
        $data = [
            'menu' => 'Obat',
        ];
        return view('admin.obat', $data);
    }
    function obat(){
        $obat = Obat::all();
        return DataTables::of($obat)
            ->addColumn('harga', function ($row) {
                return $row->harga;
            })
            ->make(true);
    }
    function create(Request $request){
        try {
            $timeZone = new CarbonTimeZone('Asia/Jakarta');
            $dateNow = Carbon::now($timeZone);

            Obat::create([
                'nama' => $request['nama'],
                'deskripsi' => $request['deskripsi'],
                'stock' => $request['stock'],
                'harga_jual' => $request['harga_jual'],
                'harga_modal' => $request['harga_modal'],
            ]);
        } catch (Exception $e) {
            Log::error('\Error in store method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan! Periksa log untuk detail.');
            // return back()->with('error' . $e->getMessage() , 'Tambah Obat Gagal! Isi Form Dengan Benar');
        }
        return back()->with('success', 'Tambah Obat Berhasil');
    }
    function update(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'id_barang' => 'required|exists:obat,id',
                'nama_barang' => 'required|string|max:255',
                'fdeskripsi' => 'required|string|max:255',
                'fhargaj' => 'required|numeric',
                'fhargam' => 'required|numeric',
                'fstock' => 'required|integer',
            ]);

            // Find the record by ID
            $obat = Obat::findOrFail($request->id_barang);

            // Update the record
            $obat->nama = $request->nama_barang;
            $obat->deskripsi = $request->fdeskripsi;
            $obat->harga_jual = $request->fhargaj;
            $obat->harga_modal = $request->fhargam;
            $obat->stock = $request->fstock;
            $obat->save();

            return back()->with('success', 'Edit Obat Berhasil !');

        } catch (Exception $e) {
            Log::error('\Error in store method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan! Periksa log untuk detail.');
            // return back()->with('error', 'Edit Obat Gagal! Isi Form Dengan Benar');
        }
    }
    function delete($id)
    {
        try{
            $obat = Obat::findOrFail($id);
            // Delete the item
            $obat->delete();

            // Return a success response
            return back()->with('success', 'Hapus Obat Berhasil !');
        }catch(Exception $e){
            return back()->with('error', 'Hapus Obat Gagal! Data Tidak Ditemukan');
        }

    }
}
