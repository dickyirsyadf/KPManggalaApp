<?php

use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KontrakIklanController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\DaftarGajiController;
use App\Http\Controllers\DashboardController;
use App\http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PenjualanController;
use FontLib\Table\Type\name;

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
            Route::get('barang','index')->name('barang.index');
            Route::get('barang/data','barang')->name('barang.data');
            Route::post('/tambahbarang', 'create')->name('barang.store');
            Route::post('/barang/edit', 'update')->name('barang.update');
            Route::delete('/barang/{id}','delete')->name('barang.delete');

        });
        Route::controller(KaryawanController::class)->group(function () {
            Route::get('karyawan','index')->name('karyawan.index');
            Route::get('kayawan/data','karyawan')->name('karyawan.data');
            Route::get('/karyawan/get-hakakses/{id}', 'getHakAksesById')->name('karyawan.hakakases');
            Route::post('/tambahkaryawan', 'create')->name('karyawan.store');
            Route::post('/karyawan/edit', 'update')->name('karyawan.update');
            Route::delete('/karyawan/{id}','delete')->name('karyawan.delete');

        });
        Route::controller(PreorderController::class)->group(function () {
            Route::get('/preorder-admin', 'indexAdmin')->name('preorder.admin');
            Route::post('/preorder/update-status/{id}', 'updateStatus')->name('preorder.updateStatus');
        });
        Route::controller(KontrakIklanController::class)->group(function () {
            Route::get('/kontrak-admin', 'indexAdmin')->name('kontrak.admin');
            Route::post('/kontrak/update-status/{id_kontrak}', 'updateStatus')->name('kontrak.updateStatus');
        });
        Route::controller(DaftarGajiController::class)->group(function () {
            Route::get('/daftargaji', 'index')->name('daftargaji.index');
            Route::get('/daftargaji/data','data')->name('daftargaji.data');
            Route::post('/daftargaji', 'store')->name('daftargaji.store');
            Route::post('/daftargaji/update', 'update')->name('daftargaji.update');
            Route::delete('/daftargaji/{id}', 'destroy')->name('daftargaji.destroy');
            Route::get('/daftargaji/{id}', 'show')->name('daftargaji.show');
        });
        Route::controller(AbsensiController::class)->group(function () {
            Route::get('/absensi', 'indexAdmin')->name('absensi.index');
            Route::post('/absensi/process', 'process')->name('absensi.process');
            Route::put('/absensi/update','update')->name('absensi.update');
            Route::get('/absensi/status/{user}/{tanggal}','cekStatusAbsensi')->name('absensi.status');
            Route::post('/absensi/status', 'recordStatus')->name('absensi.recordStatus');
        });
        Route::controller(PenggajianController::class)->group(function () {
            Route::get('/penggajian', 'index')->name('penggajian.index');
            Route::post('/penggajian', 'store')->name('penggajian.store');
            Route::get('/penggajian/slip/{id}','generateSlip')->name('penggajian.slip');
            Route::get('/penggajian/print/{id}', 'printSlip')->name('penggajian.print');
            Route::get('/penggajian/getGaji/{id}','getGaji')->name('penggajian.getGaji');

        });
        Route::controller(LaporanController::class)->group(function () {
            Route::get('laporan-keuangan', 'laporan')->name('laporan.index');
            Route::get('laporan-keuangan/export','exportLaporan')->name('laporan.export');
        });
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('admin.dashboard');
        });
    });
});
Route::middleware(['auth', 'id_hakakses:2'])->group(function () {
    Route::prefix('karyawan')->group(function ()  {
        Route::controller(PenjualanController::class)->group(function (){
            Route::get('penjualan','index')->name('penjualan.index');
            Route::post('/penjualan', 'store')->name('penjualan.store');
        });
        Route::controller(PreorderController::class)->group(function () {
            Route::get('/preorder-staff', 'indexStaff')->name('preorder.staff');
            Route::post('/preorder-staff', 'store')->name('preorder.store');
            Route::post('/preorder/selesaikan/{id}', 'selesaikan')->name('preorder.selesaikan');
        });
        Route::controller(KontrakIklanController::class)->group(function () {
            Route::get('/kontrak-staff', 'indexStaff')->name('kontrak.staff');
            Route::post('/kontrak-staff', 'store')->name('kontrak.store');
            Route::post('/kontrak/update-tayang/{id_kontrak}', 'updateTayang')->name('kontrak.updateTayang');
            Route::post('/kontrak/selesaikan/{id_kontrak}', 'selesaikan')->name('kontrak.selesaikan');
        });
        Route::controller(AbsensiController::class)->group(function () {
            Route::get('/absensi', 'indexKaryawan')->name('absensi.index');
            Route::post('/absensi/process', 'process')->name('absensi.process');
            Route::put('/absensi/update','update')->name('absensi.update');
            Route::get('/absensi/status/{user}/{tanggal}','cekStatusAbsensi')->name('absensi.status');
            Route::post('/absensi/status', 'recordStatus')->name('absensi.recordStatus');
        });
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('admin.dashboard');
        });
    });
});
