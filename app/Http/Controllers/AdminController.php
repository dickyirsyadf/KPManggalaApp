<?php

namespace App\Http\Controllers;

use App\Models\Activity_Admin;
use App\Models\Dokumentasi;
use App\Models\Dokumentasi_Foto;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Exception;

use App\Models\Muzakki;
use App\Models\Transaksi;
use App\Models\Jenis_Transaksi;
use App\Models\Program;

use Dompdf\Dompdf;
// use Barryvdh\DomPDF\PDF;
// use PDF;
// use Barryvdh\DomPDF\Facade as DomPDF;

class AdminController extends Controller
{
    function getTransaksiData($jenis)
    {
        $transaksiData = Transaksi::join('jenis_transaksi', 'transaksi.id_jenis_transaksi', '=', 'jenis_transaksi.id')
            ->where('jenis_transaksi.jenis_transaksi', 'LIKE', $jenis)
            ->get();

        return $transaksiData;
    }

    function transaksiData($id_muzakki, $no_transaksi, $nominal_transaksi, $jenis_transaksi, $tanggal_transaksi, $tanggal_konfirmasi)
    {
        return [
            'id_muzakki' => $id_muzakki,
            'no_transaksi' => $no_transaksi,
            'nominal_transaksi' => $nominal_transaksi,
            'id_jenis_transaksi' => $jenis_transaksi,
            'status' => 'Bayar',
            'tanggal_transaksi' => $tanggal_transaksi,
            'tanggal_konfirmasi' => $tanggal_konfirmasi,
        ];
    }

    function getJenisTransaksiData($jenisTransaksi)
    {
        $jenisTransaksiData = Jenis_Transaksi::where('jenis_transaksi', 'LIKE', $jenisTransaksi)
            ->get();

        return $jenisTransaksiData;
    }

    function dashboard()
    {
        $transactions = Transaksi::all();
        $data = [
            'menu' => 'Dashboard',
            'transactions' => $transactions,
        ];
        return view('admin.dashboard', $data);
    }

    function zakat()
    {
        $data = [
            'menu' => 'Zakat',
            'transaksi' => $this->getTransaksiData('%zakat%'),
            'transactions' => $this->getTransaksiData('%zakat%'),
            'jenis_transaksi' => $this->getJenisTransaksiData('%zakat%')
        ];
        return view('admin.zakat', $data);
    }

    function barang()
    {
        $data = [
            'menu' => 'Barang',
            'transaksi' => $this->getTransaksiData('%infaq%'),
            'transactions' => $this->getTransaksiData('%infaq%'),
            'jenis_transaksi' => $this->getJenisTransaksiData('%infaq%')
        ];
        return view('admin.barang', $data);
    }

    function karyawan()
    {
        $data = [
            'menu' => 'Karyawan',
            'transaksi' => $this->getTransaksiData('%sedekah%'),
            'transactions' => $this->getTransaksiData('%sedekah%'),
            'jenis_transaksi' => $this->getJenisTransaksiData('%sedekah%')
        ];
        return view('admin.karyawan', $data);
    }

    function fidyah()
    {
        $data = [
            'menu' => 'Fidyah',
            'transaksi' => $this->getTransaksiData('%fidyah%'),
            'transactions' => $this->getTransaksiData('%fidyah%'),
            'jenis_transaksi' => $this->getJenisTransaksiData('%fidyah%')
        ];
        return view('admin.fidyah', $data);
    }

    function createtransaksi(Request $request)
    {
        $request['nominal_transaksi'] = str_replace(["Rp.", "."], "", $request['nominal_transaksi']);
        try {
            $credentials = $request->validate([
                'nama' => 'required|string',
                'alamat' => 'required|string',
                'no_hp' => 'required|numeric|min:11',
                'kategori' => 'required',
                'nominal_transaksi' => 'required'
            ]);

            $timeZone = new CarbonTimeZone('Asia/Jakarta');
            $dateNow = Carbon::now($timeZone);

            $randNumbers = rand(11111, 99999);
            $randDates = str_replace(['-', ':', ' '], '', $dateNow);
            $no_transaksi = $randNumbers . $randDates;
            Validator::make(['no_transaksi' => $no_transaksi], [
                'no_transaksi' => ['unique:transaksi, no_transaksi']
            ]);

            $kategoriMapping = [
                'Zakat Maal (Harta)' => 1,
                'Zakat Penghasilan' => 3,
                'Zakat Fitrah' => 2,
                'Infaq' => 4,
                'Sedekah' => 5,
                'Fidyah' => 6
            ];

            $idJenisTransaksi = $kategoriMapping[$credentials['kategori']] ?? $credentials['kategori'];

            $foundNoHP = Muzakki::where('no_hp', $credentials['no_hp'])->first();

            $transaksiData = $this->transaksiData(
                $foundNoHP ? $foundNoHP->id : null,
                $no_transaksi,
                $credentials['nominal_transaksi'],
                $idJenisTransaksi,
                $dateNow,
                $dateNow
            );

            if ($foundNoHP) {
                $transaksi = Transaksi::create($transaksiData);
                Activity_Admin::create([
                    'nama' => Auth::user()->nama,
                    'keterangan' => 'Create Transaksi ' . $credentials['kategori'],
                    'no_transaksi' => $transaksi->no_transaksi,
                    'tanggal' => $dateNow
                ]);
                return back()->with('success', 'No HP Muzzaki Terdeteksi. Transaksi ' . $credentials['kategori'] . ' Berhasil Dibuat');
            }

            if (!$foundNoHP) {
                $muzakki = Muzakki::create([
                    'nama' => $credentials['nama'],
                    'alamat' => $credentials['alamat'],
                    'no_hp' => $credentials['no_hp']
                ]);

                $transaksiData['id_muzakki'] = $muzakki->id;
                $transaksi = Transaksi::create($transaksiData);

                Activity_Admin::create([
                    'nama' => Auth::user()->nama,
                    'keterangan' => 'Create Transaksi ' . $credentials['kategori'],
                    'no_transaksi' => $transaksi->no_transaksi,
                    'tanggal' => $dateNow
                ]);

                return back()->with('success', 'Transaksi ' . $credentials['kategori'] . ' Berhasil Dibuat Dan Muzzaki Baru Berhasil Ditambahkan');
            }
        } catch (Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', 'Transaksi ' . $request['kategori'] . ' Gagal! Isi Form Dengan Benar');
        }
    }

    function updatetransaksi(Request $request)
    {

        $timeZone = new CarbonTimeZone('Asia/Jakarta');
        $dateNow = Carbon::now($timeZone);

        $transaksi = Transaksi::where('no_transaksi', $request['no_transaksi'])
            ->update(['keterangan' => $request['keterangan']]);

        Activity_Admin::create([
            'nama' => Auth::user()->nama,
            'keterangan' => 'Tambah Keterangan Transaksi',
            'no_transaksi' => $request['no_transaksi'],
            'tanggal' => $dateNow
        ]);

        return back()->with('success', 'Tambah Keterangan Berhasil');
    }

    function program()
    {
        $data = [
            'menu' => 'Program',
            // 'transactions' => $this->getTransaksiData('%zakat%'),
            'transactions' => Transaksi::all(),
            'programs' => Program::get(),
        ];
        return view('admin.program', $data);
    }

    function createprogram(Request $request)
    {
        try {
            $timeZone = new CarbonTimeZone('Asia/Jakarta');
            $dateNow = Carbon::now($timeZone);

            $credentials = $request->validate([
                'nama_program' => 'required|string'
            ]);

            $program = Program::create([
                'nama_program' => $credentials['nama_program']
            ]);

            Activity_Admin::create([
                'nama' => Auth::user()->nama,
                'keterangan' => 'Create Program ' . $credentials['nama_program'],
                'no_transaksi' => $program->id,
                'tanggal' => $dateNow
            ]);

            return back()->with('success', 'Program Berhasil Dibuat');
        } catch (Exception $e) {
            info('gagal bikin program', ["ctx" => $e]);
            return back()->with('error', "Program Gagal Dibuat. Isi Form Dengan Benar!!");
        }
    }

    function updateprogram(Request $request)
    {
        try {
            $timeZone = new CarbonTimeZone('Asia/Jakarta');
            $dateNow = Carbon::now($timeZone);

            if ($request['nominal_terkumpul']) {
                $request['nominal_terkumpul'] = str_replace(["Rp.", "."], "", $request['nominal_terkumpul']);
                $program = Program::find($request['id']);
                $program->update(['nominal_terkumpul' => $request['nominal_terkumpul']]);

                Activity_Admin::create([
                    'nama' => Auth::user()->nama,
                    'keterangan' => 'Tambah Nominal Program ' . $request['nama_program'],
                    'no_transaksi' => $program->id,
                    'tanggal' => $dateNow
                ]);

                return back()->with('success', 'Tambah Nominal Berhasil!');
            }

            if ($request['keterangan']) {
                $program = Program::find($request['id']);
                $program->update(['keterangan' => $request['keterangan']]);

                Activity_Admin::create([
                    'nama' => Auth::user()->nama,
                    'keterangan' => 'Tambah Keterangan Program ' . $request['nama_program'] . $request['keterangan'],
                    'no_transaksi' => $program->id,
                    'tanggal' => $dateNow
                ]);

                return back()->with('success', 'Tambah Keterangan Berhasil!');
            }

            if ($request['nama_program'] && !$request['nominal_terkumpul']) {
                $program = Program::find($request['id']);
                $program->update(['status' => "Selesai"]);

                Activity_Admin::create([
                    'nama' => Auth::user()->nama,
                    'keterangan' => 'Konfirmasi Program Selesai ' . $request['nama_program'] . $request['keterangan'],
                    'no_transaksi' => $program->id,
                    'tanggal' => $dateNow
                ]);

                return back()->with('success', 'Konfirmasi Program Selesai Berhasil!');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    function konfirmasi(Request $request)
    {
        $data = [
            'menu' => 'Konfirmasi',
            'transactions' => Transaksi::where('status', 'proses')->get(),
        ];
        return view('admin.konfirmasi', $data);
    }

    function dokumentasi()
    {
        $data = [
            'menu' => 'Dokumentasi',
            'dokumentasis' => Dokumentasi::get(),
            'transactions' => Transaksi::all(),

            'dokumentasi_fotos' => Dokumentasi_Foto::get(),
        ];

        return view('admin.dokumentasi', $data);
    }

    function createdokumentasi(Request $request)
    {
        $timeZone = new CarbonTimeZone('Asia/Jakarta');
        $dateNow = Carbon::now($timeZone);
        try {
            $credentials = $request->validate([
                'judul' => 'required|string',
                'deskripsi' => 'required|string'
            ]);

            $judul = str_replace(' ', '_', $credentials['judul']); // Mengganti spasi dengan '_'

            Dokumentasi::create([
                'judul' => $judul,
                'deskripsi' => $credentials['deskripsi'],
                'tanggal_dokumentasi' => $dateNow
            ]);
            return back()->with('success', 'Berhasil Upload Judul Dan Deskripsi');
        } catch (Exception $e) {
            dd($e->getMessage());
            // return back()->with('error', 'Isi Form Judul / Deskripsi Dengan Benar!');
        }
    }

    function updatedokumentasi(Request $request)
    {
        // Validasi permintaan jika diperlukan
        $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        $judulInput = $request->input('judul');
        $deskripsiInput = $request->input('deskripsi');

        // Mengubah semua huruf dalam judul menjadi huruf kecil dan ganti spasi dengan garis bawah
        $judul = str_replace(' ', '_', strtolower($judulInput));

        $dok = Dokumentasi::find($request->input('id'));

        if ($dok->judul === $judul && $dok->deskripsi === $deskripsiInput) {
            // return response json with success message
            return response()->json(['message' => 'Data Judul dan Deskripsi Tetap Sama. Tidak Ada yang Di Perbaharui', 'status' => 'warning'], 200);
        }

        $oldFolderName = 'public/dokumentasi/' . $dok->judul;
        $newFolderName = 'public/dokumentasi/' . $judul;

        if ($dok->judul != $judul) {
            if (Storage::exists($oldFolderName)) {
                Storage::move($oldFolderName, $newFolderName);
                Dokumentasi_Foto::where('folder', $dok->judul)->update(['folder' => $judul]);
            } else {
                // return response json with error message
                return response()->json(['message' => 'Data Judul Gagal Diperbaharui', 'status' => 'error'], 400);
            }
        }

        if ($dok->deskripsi != $deskripsiInput) {
            $dok->update(['deskripsi' => $deskripsiInput]);
        }

        $dok->update(['judul' => $judul]);

        // return response json with success message
        return response()->json(['message' => 'Data Berhasil diperbarui', 'status' => 'success'], 200);
    }

    function hapus(Request $request)
    {
        try {
            // Validasi permintaan jika diperlukan, Anda dapat menambahkannya di sini

            // Mendapatkan nama gambar dari permintaan
            $imageName = $request->input('img'); // Sesuaikan dengan data yang Anda kirimkan

            // return response()->json(['status' => 'success', 'message' => $imageName], 200);


            // Cari data gambar berdasarkan nama gambar
            $gambar = Dokumentasi_Foto::where('foto', $imageName)->first();

            // return response()->json(['status' => 'success', 'message' => $gambar->foto], 200);


            if ($gambar) {
                // Hapus gambar dari penyimpanan
                $path = storage_path('app/public/dokumentasi/' . $gambar->folder . '/');
                $pathImg = $path . $gambar->foto;

                if (File::exists($path)) {
                    $directoryPath = storage_path('app/public/dokumentasi/' . $gambar->folder);

                    if (File::isDirectory($directoryPath)) {
                        $directoryContents = scandir($directoryPath);
                        foreach ($directoryContents as $file) {
                            if ($file === $imageName) {
                                $filePath = $directoryPath . '/' . $file;
                                File::delete($filePath);

                                // delete record in table temporaryFotos
                                Dokumentasi_Foto::where([
                                    'foto' => $gambar->foto,
                                ])->delete();

                                $dir = scandir($directoryPath);
                                if (count($dir) <= 2) {
                                    rmdir($directoryPath);
                                    // return 'success delete directory';
                                }

                                return response()->json(['status' => 'success', 'message' => 'Foto Berhasil di Hapus'], 200);
                            }
                        }
                    }
                }
            } else {
                // Kirim respons gagal jika data gambar tidak ditemukan di database
                return response()->json(['status' => 'error', 'message' => 'Data gambar tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            // Tangani kesalahan dan kirim respons kesalahan ke klien
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    function deleteDokumentasi(Request $request)
    {
        try {
            $judul_raw = $request->input('judul');

            $folder = Dokumentasi_Foto::where('folder', $judul_raw)->first();

            $judul = Dokumentasi::where('judul', $judul_raw)->first();

            $judulFormatted = ucwords(str_replace('_', ' ', $judul_raw));


            if ($judul) {
                if ($folder) {
                    // Hapus gambar dari penyimpanan
                    $path = storage_path('app/public/dokumentasi/' . $folder->folder . '/');

                    if (File::exists($path)) {
                        File::deleteDirectory($path);

                        Dokumentasi_Foto::where('folder', $judul_raw)->delete();
                        Dokumentasi::where('judul', $judul_raw)->update(['status' => 'hapus']);

                        return response()->json(['message' => $judulFormatted . ' Dan Images ', 'status' => 'success'], 200);
                    }
                } else {
                    $jUpdate = Dokumentasi::where('judul', $judul_raw)->update(['status' => 'hapus']);
                    if ($jUpdate) {
                        return response()->json(['message' => $judulFormatted, 'status' => 'success'], 200);
                    }
                    $judulFormatted = ucwords(str_replace('_', ' ', $judul_raw));
                    return response()->json(['message' => $judulFormatted, 'status' => 'error'], 200);
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    //function filepond untuk uploud images
    function upload(Request $request)
    {
        $timeZone = new CarbonTimeZone('Asia/Jakarta');
        $dateNow = Carbon::now($timeZone);
        try {
            $judul = $request->header('X-Judul');
            $judul_decoded = urldecode($judul);
            $judul_clean = str_replace(' ', '_', $judul_decoded);

            //process upload form filepond
            $files = $request->file('images');
            $filename = hexdec(uniqid()) . '.' . $files->extension();

            $path = storage_path('app/public/dokumentasi/') . $judul_clean . '/';
            if (!File::exists($path)) {
                mkdir($path, 0755, true);
            }

            if (File::exists($path)) {
                $files->storeAs('public/dokumentasi/' . $judul_clean, $filename);
            }

            if ($path) {
                Dokumentasi_Foto::create([
                    'folder' => $judul_clean,
                    'foto' => $filename
                ]);
            }
            return $filename;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    //function filepond untuk destroy images
    function destroy(Request $request)
    {
        try {
            $image = $request->query('images');
            $dkm = Dokumentasi_Foto::where('foto', $image)->first();

            if ($dkm) {
                $path = storage_path() . '/app/public/dokumentasi/' . $dkm->folder . '/' . $dkm->images;
                if (File::exists($path)) {

                    $directoryPath = storage_path('app/public/dokumentasi/' . $dkm->folder);

                    if (File::isDirectory($directoryPath)) {
                        $directoryContents = scandir($directoryPath);
                        foreach ($directoryContents as $file) {
                            if ($file === $image) {
                                $filePath = $directoryPath . '/' . $file;
                                File::delete($filePath);

                                // delete record in table temporaryFotos
                                Dokumentasi_Foto::where([
                                    'foto' => $dkm->foto,
                                ])->delete();

                                $dir = scandir($directoryPath);
                                if (count($dir) <= 2) {
                                    rmdir($directoryPath);
                                    // return 'success delete directory';
                                }
                                // return 'success delete file : ' . $file;
                            }
                        }
                    }
                }
                // return 'path not found';
            }
            // return 'not found';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function getImages(Request $request)
    {
        try {
            $folder = $request['file'];
            $path = storage_path('app/public/dokumentasi/' . $folder);
            $scanPath = scandir($path);

            // Menghilangkan "." dan ".." dari daftar
            $filteredScanPath = [];
            foreach ($scanPath as $entry) {
                if ($entry !== '.' && $entry !== '..') {
                    $filteredScanPath[] = $entry;
                }
            }

            return $filteredScanPath;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function pengeluaran(Request $request)
    {
        $transactions = Transaksi::where('status', 'Bayar')->get();
        $totalNominal = 0;

        foreach ($transactions as $transaction) {
            $totalNominal += $transaction->nominal_transaksi;
        }

        $data = [
            'menu' => 'Pengeluaran',
            'pengeluaran' => '',
            'totalNominal' => $totalNominal
        ];

        return view('admin.pengeluaran', $data);
    }

    function createpengeluaran(Request $request)
    {
        try {
            dd($request['pengeluaran']);
        } catch (Exception $e) {
        }
    }

    function laporan(Request $request)
    {
        $data = [
            'menu' => 'Laporan',
            'transactions' => Transaksi::get(),
            // 'dokumentasi_fotos' => Dokumentasi_Foto::get(),
        ];

        return view('admin.laporan', $data);
    }

    function exportPDF(Request $request)
    {
        try {

            $timeZone = new CarbonTimeZone('Asia/Jakarta');
            $dateNow = Carbon::now($timeZone);

            $bulan = $request['bulan'];
            $tahun = $request['tahun'];

            $transactions = Transaksi::whereYear('tanggal_transaksi', $tahun)
                ->whereMonth('tanggal_transaksi', $bulan)
                ->get();

            if ($bulan >= "01" && $bulan <= "12") {
                $nama_bulan = [
                    "01" => "Januari",
                    "02" => "Februari",
                    "03" => "Maret",
                    "04" => "April",
                    "05" => "Mei",
                    "06" => "Juni",
                    "07" => "Juli",
                    "08" => "Agustus",
                    "09" => "September",
                    "10" => "Oktober",
                    "11" => "November",
                    "12" => "Desember"
                ];

                $bulan = $nama_bulan[$bulan];
            }

            $dompdf = new Dompdf();

            $html = '<html>
                        <head>
                            <style>
                                @page { margin:0px; }

                                body {
                                    font-family: sans-serif;
                                }

                            </style>
                        </head>
                        <body>
                            <div style="text-align: right; margin-top: 10px; margin-right: 10px;">
                                <span> <u> Tanggal Cetak : ' . $dateNow . ' </u></span>
                            </div>
                            <div style="margin: 5px 15px;">
                                <div style="text-align: center; ">
                                    <h4 style="margin: 0; font-weight: normal;">Pondok Pesantren Al-Falah Dago Kota Bandung</h4>
                                    <h3 style="margin: 0;"><u> Laporan Uang Masuk Kencleng Umat </u></h3>
                                    <h4 style="margin: 0; font-weight: normal;">Periode : ' . $bulan . ' ' . $tahun . '</h4>
                                </div>
                                <br>

                                <table style="width:100%; border-collapse: collapse;">
                                    <tr>
                                        <th style="border-top: 1px solid black; border-bottom: 1px solid black; border-left: none; border-right: none;">No</th>
                                        <th style="border-top: 1px solid black; border-bottom: 1px solid black; border-left: none; border-right: none;">ID Transaksi</th>
                                        <th style="border-top: 1px solid black; border-bottom: 1px solid black; border-left: none; border-right: none;">Jenis</th>
                                        <th style="border-top: 1px solid black; border-bottom: 1px solid black; border-left: none; border-right: none;">Tanggal</th>
                                        <th style="border-top: 1px solid black; border-bottom: 1px solid black; border-left: none; border-right: none;">Nominal</th>
                                    </tr>';

            $zakatMaal = 0;
            $zakatFitrah = 0;
            $zakatPenghasilan = 0;
            $infaq = 0;
            $sedekah = 0;
            $fidyah = 0;

            $totalZakatMaal = 0;
            $totalZakatPenghasilan = 0;
            $totalZakatFitrah = 0;
            $totalInfaq = 0;
            $totalSedekah = 0;
            $totalFidyah = 0;

            $totalNominal = 0;
            $nomor = 1; // Variabel untuk nomor urut

            foreach ($transactions as $transaction) {
                $html .= '<tr>';
                $html .= '<td style="text-align: center; border-left: 1px solid black;">' . $nomor . '</td>'; // Menambahkan nomor urut
                $html .= '<td style="text-align: center;">' . $transaction->no_transaksi . '</td>';
                $html .= '<td style="text-align: center;">' . $transaction->jenis_transaksi->jenis_transaksi . '</td>';
                $html .= '<td style="text-align: center;">' . $transaction->tanggal_transaksi . '</td>';
                $html .= '<td style="padding-left: 50px; border-right: 1px solid black;">' . number_format($transaction->nominal_transaksi, 0, ',', '.') . '</td>';
                $html .= '</tr>';


                if ($transaction->jenis_transaksi->jenis_transaksi === 'Zakat Maal (Harta)') {
                    $totalZakatMaal += $transaction->nominal_transaksi;
                    $zakatMaal += 1;
                } elseif ($transaction->jenis_transaksi->jenis_transaksi === 'Zakat Penghasilan') {
                    $totalZakatPenghasilan += $transaction->nominal_transaksi;
                    $zakatPenghasilan += 1;
                } elseif ($transaction->jenis_transaksi->jenis_transaksi === 'Zakat Fitrah') {
                    $totalZakatFitrah += $transaction->nominal_transaksi;
                    $zakatFitrah += 1;
                } elseif ($transaction->jenis_transaksi->jenis_transaksi === 'Infaq') {
                    $totalInfaq += $transaction->nominal_transaksi;
                    $infaq += 1;
                } elseif ($transaction->jenis_transaksi->jenis_transaksi === 'Sedekah') {
                    $totalSedekah += $transaction->nominal_transaksi;
                    $sedekah += 1;
                } elseif ($transaction->jenis_transaksi->jenis_transaksi === 'Fidyah') {
                    $totalFidyah += $transaction->nominal_transaksi;
                    $fidyah += 1;
                }

                $totalNominal += $transaction->nominal_transaksi; // Akumulasi total nominal
                $nomor++; // Tingkatkan nomor urut
            }
            $html .= '<tr>
                                            <td style="border-top: 1px solid black;"></td>
                                            <td style="border-top: 1px solid black;"></td>
                                            <td style="border-top: 1px solid black;"></td>
                                            <td style="border-top: 1px solid black;"></td>
                                            <td style="border-top: 1px solid black;"></td>
                                        </tr>';
            $html .= '<br>';

            //zakat maal
            $html .= '<tr>
                                    <th style="background-color:#D0D4CA; text-align: left;  border-bottom: 1px solid grey; border-left: none; border-right: none;"> Zakat Maal (Harta) </th>
                                    <td style="background-color:#D0D4CA;  border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <td style="background-color:#D0D4CA; text-align:center;  border-bottom: 1px solid grey; border-left: none; border-right: none;">' . $zakatMaal . '</td>
                                    <td style="background-color:#D0D4CA;  border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <th style="background-color:#D0D4CA;  border-bottom: 1px solid grey; border-left: none; border-right: none;">' . number_format($totalZakatMaal, 0, ',', '.') . '  </th>
                                </tr>';

            //zakat fitrah
            $html .= '<tr>
                                    <th style="text-align: left; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"> Zakat Fitrah </th>
                                    <td style="border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <td style="text-align:center; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . $zakatFitrah . '</td>
                                    <td style="border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <th style="border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . number_format($totalZakatFitrah, 0, ',', '.') . ' </th>
                                </tr>';

            //zakat penghasilan
            $html .= '<tr>
                                    <th style="background-color:#D0D4CA; text-align: left; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"> Zakat Penghasilan </th>
                                    <td style="background-color:#D0D4CA; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <td style="background-color:#D0D4CA; text-align:center; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . $zakatPenghasilan . '</td>
                                    <td style="background-color:#D0D4CA; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <th style="background-color:#D0D4CA; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . number_format($totalZakatPenghasilan, 0, ',', '.') . ' </th>
                                </tr>';

            //Infaq
            $html .= '<tr>
                                    <th style="text-align: left; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"> Infaq </th>
                                    <td style="border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <td style="text-align:center; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . $infaq . '</td>
                                    <td style="border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <th style="border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . number_format($totalInfaq, 0, ',', '.') . ' </th>
                                </tr>';

            //Sedekah
            $html .= '<tr>
                                    <th style="background-color:#D0D4CA; text-align: left; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"> Sedekah </th>
                                    <td style="background-color:#D0D4CA; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <td style="background-color:#D0D4CA; text-align:center; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . $sedekah . '</td>
                                    <td style="background-color:#D0D4CA; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <th style="background-color:#D0D4CA; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . number_format($totalSedekah, 0, ',', '.') . ' </th>
                                </tr>';

            //Fidyah
            $html .= '<tr>
                                    <th style="text-align: left; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"> Fidyah </th>
                                    <td style="border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <td style="text-align:center; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . $fidyah . '</td>
                                    <td style="border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <th style="text-align:center; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;">' . number_format($totalFidyah, 0, ',', '.') . ' </th>
                                </tr>';

            $html .= '<tr>
                                    <th style="background-color:#D0D4CA; text-align: left; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"> Total </th>
                                    <td style="background-color:#D0D4CA; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <td style="background-color:#D0D4CA; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <td style="background-color:#D0D4CA; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"></td>
                                    <th style="background-color:#D0D4CA; text-align:center; border-top: 1px solid grey; border-bottom: 1px solid grey; border-left: none; border-right: none;"> ' . number_format($totalNominal, 0, ',', '.') . ' </th>
                                </tr>
                            </table>
                        </div>
                    </body>
                    </html>';


            $dompdf->loadHtml($html);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'landscape');

            // Render the HTML as PDF
            $dompdf->render();

            // Simpan PDF dalam variabel dan encode ke base64
            $pdfContent = base64_encode($dompdf->output());

            // Mengirimkan respons JSON dengan data base64
            return response()->json(['pdfResponse' => $pdfContent, 'status' => 'success'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal membuat PDF: ' . $e->getMessage()], 500);
        }
    }
}
