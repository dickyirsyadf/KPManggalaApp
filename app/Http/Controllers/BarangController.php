<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class BarangController extends Controller
{
    function index(){
        $data = [
            'menu' => 'Barang',
        ];
        return view('admin.barang', $data);
    }
    function Barang(){
        $Barang = Barang::all();
        return DataTables::of($Barang)
            ->addColumn('harga', function ($row) {
                return $row->harga;
            })
            ->make(true);
    }
    function create(Request $request){
        try {
            $timeZone = new CarbonTimeZone('Asia/Jakarta');
            $dateNow = Carbon::now($timeZone);

            Barang::create([
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
            // return back()->with('error' . $e->getMessage() , 'Tambah Barang Gagal! Isi Form Dengan Benar');
        }
        return back()->with('success', 'Tambah Barang Berhasil');
    }
    function update(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'id_barang' => 'required|exists:Barang,id',
                'nama_barang' => 'required|string|max:255',
                'fdeskripsi' => 'required|string|max:255',
                'fhargaj' => 'required|numeric',
                'fhargam' => 'required|numeric',
                'fstock' => 'required|integer',
            ]);

            // Find the record by ID
            $Barang = Barang::findOrFail($request->id_barang);

            // Update the record
            $Barang->nama = $request->nama_barang;
            $Barang->deskripsi = $request->fdeskripsi;
            $Barang->harga_jual = $request->fhargaj;
            $Barang->harga_modal = $request->fhargam;
            $Barang->stock = $request->fstock;
            $Barang->save();

            return back()->with('success', 'Edit Barang Berhasil !');

        } catch (Exception $e) {
            Log::error('\Error in store method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan! Periksa log untuk detail.');
            // return back()->with('error', 'Edit Barang Gagal! Isi Form Dengan Benar');
        }
    }
    function delete($id)
    {
        try{
            $Barang = Barang::findOrFail($id);
            // Delete the item
            $Barang->delete();

            // Return a success response
            return back()->with('success', 'Hapus Barang Berhasil !');
        }catch(Exception $e){
            return back()->with('error', 'Hapus Barang Gagal! Data Tidak Ditemukan');
        }

    }
}
