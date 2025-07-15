<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AbsensiController extends Controller
{
    public function index()
    {
        $users = User::all();
        $absensi = Absensi::with('user')->orderBy('tanggal', 'desc')->paginate(10);
        $data = ['menu' => 'Absensi'];

        return view('admin.absensi', compact('users', 'absensi', 'data'));
    }

    /**
     * Method ini menangani Jam Masuk/Keluar dengan logika keterlambatan dan lembur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:users,id',
            'tanggal' => 'required|date',
        ]);

        $absensi = Absensi::where('id_karyawan', $request->id_karyawan)
                           ->where('tanggal', $request->tanggal)
                           ->first();

        // Menggunakan zona waktu Jakarta (WIB)
        $timezone = 'Asia/Jakarta';
        $currentTime = now($timezone);
        $batasWaktuMasuk = Carbon::parse($request->tanggal . ' 09:15:00', $timezone);
        $batasWaktuLembur = Carbon::parse($request->tanggal . ' 17:30:00', $timezone);

        // KASUS 1: Absen Masuk
        if (!$absensi) {
            Absensi::create([
                'id_karyawan' => $request->id_karyawan,
                'tanggal' => $request->tanggal,
                'jam_masuk' => $currentTime->format('H:i:s'),
                'kehadiran' => null, // Ditentukan saat absen pulang
                'keterangan' => $currentTime->gt($batasWaktuMasuk) ? 'Terlambat' : null,
            ]);
            return redirect()->route('absensi.index')->with('success', 'Berhasil melakukan absensi masuk.');
        }

        // KASUS 2: Absen Pulang
        if ($absensi && is_null($absensi->jam_keluar)) {
            $absensi->jam_keluar = $currentTime->format('H:i:s');
            $absensi->kehadiran = 1; // Jika sudah absen pulang, dianggap hadir.

            $keterangan = [];
            // Cek apakah saat masuk sudah tercatat terlambat
            if (str_contains($absensi->keterangan ?? '', 'Terlambat')) {
                $keterangan[] = 'Terlambat';
            }
            // Cek apakah sekarang lembur
            if ($currentTime->gt($batasWaktuLembur)) {
                $keterangan[] = 'Lembur';
            }

            $absensi->keterangan = !empty($keterangan) ? implode(', ', $keterangan) : null;

            $absensi->save();
            return redirect()->route('absensi.index')->with('success', 'Berhasil melakukan absensi pulang.');
        }

        return redirect()->route('absensi.index')->with('error', 'Karyawan ini sudah selesai absen untuk hari yang dipilih.');
    }

    /**
     * Method untuk update data absensi dari modal dengan logika yang diperbaiki.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:absensi,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'jam_keluar' => 'nullable',
            'admin_password' => 'required',
        ]);

        if (!Hash::check($request->admin_password, Auth::user()->password)) {
            return redirect()->back()->withErrors(['admin_password' => 'Password admin salah.']);
        }

        $absensi = Absensi::findOrFail($request->id);

        // 1. Perbarui data dasar dari request
        $absensi->tanggal = $request->tanggal;
        $absensi->jam_masuk = $request->jam_masuk;
        $absensi->jam_keluar = $request->jam_keluar;

        // 2. Hitung ulang semua status dari awal menggunakan zona waktu Jakarta
        $timezone = 'Asia/Jakarta';
        $keteranganFinal = [];

        // Cek status "Terlambat" berdasarkan jam masuk yang baru
        $jamMasukInput = Carbon::parse($request->tanggal . ' ' . $request->jam_masuk, $timezone);
        $batasWaktuMasuk = Carbon::parse($request->tanggal . ' 09:15:00', $timezone);
        if($jamMasukInput->gt($batasWaktuMasuk)){
            $keteranganFinal[] = 'Terlambat';
        }

        // Cek status "Hadir" dan "Lembur" berdasarkan jam keluar yang baru
        if ($request->filled('jam_keluar') && $request->jam_keluar) {
            $absensi->kehadiran = 1; // Dianggap hadir jika ada jam keluar

            // Cek status "Lembur"
            $jamKeluarInput = Carbon::parse($request->tanggal . ' ' . $request->jam_keluar, $timezone);
            $batasWaktuLembur = Carbon::parse($request->tanggal . ' 17:30:00', $timezone);
            if($jamKeluarInput->gt($batasWaktuLembur)){
                $keteranganFinal[] = 'Lembur';
            }
        } else {
            // Jika tidak ada jam keluar, status kehadiran belum final
            $absensi->kehadiran = null;
        }

        // 3. Simpan keterangan yang sudah final
        $absensi->keterangan = !empty($keteranganFinal) ? implode(', ', $keteranganFinal) : null;

        // 4. Simpan semua perubahan ke database
        $absensi->save();

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Method untuk mengecek status absensi via AJAX.
     */
    public function cekStatusAbsensi(User $user, $tanggal)
    {
        $absensi = Absensi::where('id_karyawan', $user->id)
                           ->where('tanggal', $tanggal)
                           ->first();

        if (!$absensi) {
            return response()->json(['text' => 'Proses Absen Masuk', 'disabled' => false]);
        }
        if (is_null($absensi->jam_keluar)) {
            return response()->json(['text' => 'Proses Absen Pulang', 'disabled' => false]);
        }
        return response()->json(['text' => 'Sudah Selesai Absen', 'disabled' => true]);
    }
}
