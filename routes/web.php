<?php

use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarGajiController;
use App\Http\Controllers\DashboardController;
use App\http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ObatController;
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
        Route::controller(ObatController::class)->group(function () {
            Route::get('obat','index')->name('obat.index');
            Route::get('obat/data','obat')->name('obat.data');
            Route::post('/tambahobat', 'create')->name('obat.store');
            Route::post('/obat/edit', 'update')->name('obat.update');
            Route::delete('/obat/{id}','delete')->name('obat.delete');

        });
        Route::controller(KaryawanController::class)->group(function () {
            Route::get('karyawan','index')->name('karyawan.index');
            Route::get('kayawan/data','karyawan')->name('karyawan.data');
            Route::get('/karyawan/get-hakakses/{id}', 'getHakAksesById')->name('karyawan.hakakases');
            Route::post('/tambahkaryawan', 'create')->name('karyawan.store');
            Route::post('/karyawan/edit', 'update')->name('karyawan.update');
            Route::delete('/karyawan/{id}','delete')->name('karyawan.delete');

        });
        Route::controller(PenjualanController::class)->group(function (){
            Route::get('penjualan','index')->name('penjualan.index');
            Route::post('/penjualan', 'store')->name('penjualan.store');
        });
        Route::controller(DaftarGajiController::class)->group(function () {
            Route::get('/daftargaji', 'index')->name('daftargaji.index'); // Display the Daftar Gaji page
            Route::get('/daftargaji/data','data')->name('daftargaji.data');
            Route::post('/tambahgaji', 'store')->name('daftargaji.store'); // Store a new entry
            Route::post('/daftargaji/update', 'update')->name('daftargaji.update'); // Update an existing entry
            Route::delete('/daftargaji/{id}', 'destroy')->name('daftargaji.destroy'); // Delete an entry
            Route::get('/daftargaji/{id}', 'show')->name('daftargaji.show'); // Fetch data for a specific entry
        });
        Route::controller(AbsensiController::class)->group(function () {
            Route::get('/absensi', 'index')->name('absensi.index');
            Route::post('/absensi', 'store')->name('absensi.store');
            Route::put('/absensi/update','update')->name('absensi.update');

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
            Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
        });
    });
});
