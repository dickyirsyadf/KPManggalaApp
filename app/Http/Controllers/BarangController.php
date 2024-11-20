<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
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
    function barang(){
        $barang = Barang::all();
        return DataTables::of($barang)
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
                'harga' => $request['harga'],
            ]);

        } catch (Exception $e) {
            // dd($e->getMessage());
            return back()->with('error' . $e->getMessage() , 'Tambah barang Gagal! Isi Form Dengan Benar');
        }
        return back()->with('success', 'Tambah Barang Berhasil');
    }
    function update(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'id_barang' => 'required|exists:barang,id',
                'nama_barang' => 'required|string|max:255',
                'fdeskripsi' => 'required|string|max:255',
                'fharga' => 'required|numeric',
                'fstock' => 'required|integer',
            ]);

            // Find the record by ID
            $barang = Barang::findOrFail($request->id_barang);

            // Update the record
            $barang->nama = $request->nama_barang;
            $barang->deskripsi = $request->fdeskripsi;
            $barang->harga = $request->fharga;
            $barang->stock = $request->fstock;
            $barang->save();

            return back()->with('success', 'Edit Barang Berhasil !');

        } catch (Exception $e) {

            return back()->with('error', 'Edit barang Gagal! Isi Form Dengan Benar');
        }
    }
    function delete($id)
    {
        try{
            $barang = Barang::findOrFail($id);
            // Delete the item
            $barang->delete();
    
            // Return a success response
            return back()->with('success', 'Hapus Barang Berhasil !');
        }catch(Exception $e){
            return back()->with('error', 'Hapus barang Gagal! Data Tidak Ditemukan');
        }
        
    }
}
