<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\http\Controllers\KaryawanController;
// use PDF;
use Illuminate\Support\Facades\PDF;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware' => 'guest'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'login')->name('login');
        Route::get('login', 'login')->name('login');
        Route::get('registrasi', 'registrasi');
        Route::get('forgot-password', 'forgotpassword');

        Route::post('login', 'authentication');
        Route::post('buatakun', 'createUser');
    });
});

Route::get('logout', [AuthController::class, 'logout']);



Route::middleware(['auth', 'id_hakakses:1'])->group(function () {
    Route::prefix('admin')->group(function ()  {
        Route::controller(BarangController::class)->group(function () {
            Route::get('barang','index');
            Route::get('barang/data','barang')->name('barang.data');
            Route::post('/tambahbarang', 'create');
            Route::post('/barang/edit', 'update')->name('barang.update');
            Route::delete('/barang/{id}','delete')->name('barang.delete');
            
        });
        Route::controller(KaryawanController::class)->group(function () {
            Route::get('karyawan','index');
            Route::get('kayawan/data','karyawan')->name('karyawan.data');
            Route::get('/karyawan/get-hakakses/{id}', 'getHakAksesById')->name('karyawan.hakakases');
            Route::post('/tambahkaryawan', 'create');
            Route::post('/karyawan/edit', 'update')->name('karyawan.update');
            Route::delete('/karyawan/{id}','delete')->name('karyawan.delete');
            
        });
        Route::controller(AdminController::class)->group(function (){
            Route::get('/dashboard', 'dashboard');
            // Transaksi
            Route::get('/sedekah', 'sedekah');
            Route::get('/fidyah', 'fidyah');

            // Route::post('/tambahtransaksi', 'createtransaksi');
            Route::post('/tambahketerangan', 'updatetransaksi');

            // Program
            Route::get('/program', 'program');

            Route::post('/tambahprogram', 'createprogram');
            Route::post('/editprogram', 'updateprogram');

            // Dokumentasi
            Route::get('/dokumentasi', 'dokumentasi');
            Route::get('getImages', 'getImages')->name('getImages');

            Route::post('/tambahdokumentasi', 'createdokumentasi');
            Route::post('/update-data', 'updatedokumentasi')->name('update-data');
            Route::post('/gambar-hapus', 'hapus')->name('gambar-hapus');
            Route::post('/hapus-dokumentasi', 'deleteDokumentasi')->name('delete-dokumentasi');

            // Route Filepond
            Route::post('/upload', 'upload')->name('upload');
            Route::delete('/hapus', 'destroy')->name('hapus');

            // Route Pengeluaran
            Route::get('/pengeluaran', 'pengeluaran')->name('pengeluaran');
            Route::post('/tambahpengeluaran', 'createpengeluaran')->name('tambahpengeluaran');

            // Laporan
            Route::get('/laporan', 'laporan');
            Route::post('/generate-pdf', 'exportPDF')->name('generate-pdf');

            // Konfirmasi
            Route::get('/konfirmasi', 'konfirmasi');
        });
    });
});

Route::middleware(['auth', 'id_hakakses:2'])->group(function () {
    Route::controller(SuperAdminController::class)->group(function () {
        Route::prefix('superadmin')->group(function () {
            Route::get('/dashboard', 'dashboard');
        });
    });
});
