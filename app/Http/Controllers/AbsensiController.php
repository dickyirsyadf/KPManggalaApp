<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman absensi untuk Admin.
     * Data yang ditampilkan difilter berdasarkan pilihan di dropdown.
     * Defaultnya menampilkan data admin yang sedang login.
     */
    public function indexAdmin(Request $request)
    {
        $users = User::all();
        $loggedInUserId = Auth::id();

        // Ambil id karyawan dari request, jika tidak ada, gunakan id admin yang login
        $selectedUserId = $request->input('id_karyawan', $loggedInUserId);

        // Ambil data absensi untuk karyawan yang dipilih dalam 45 hari terakhir
        $absensi = Absensi::with('user')
                         ->where('id_karyawan', $selectedUserId)
                         ->where('tanggal', '>=', Carbon::now()->subDays(45)->toDateString())
                         ->orderBy('tanggal', 'desc')
                         ->paginate(10);

        // Pastikan parameter filter tetap ada di link pagination
        $absensi->appends($request->all());

        $data = ['menu' => 'Absensi'];

        // Kirim semua data yang diperlukan ke view
        return view('admin.absensi', compact('users', 'absensi', 'data', 'loggedInUserId', 'selectedUserId'));
    }

    /**
     * Menampilkan halaman absensi untuk Karyawan.
     * Tabel hanya menampilkan data absensi milik karyawan yang login selama 45 hari terakhir.
     */
    public function indexKaryawan()
    {
        $users = User::all();
        $loggedInUserId = Auth::id();
        $selectedUserId = $loggedInUserId; // Untuk karyawan, yang dipilih selalu dirinya sendiri

        // Mengambil data absensi HANYA untuk user yang login, dalam 45 hari terakhir
        $absensi = Absensi::with('user')
                         ->where('id_karyawan', $loggedInUserId)
                         ->where('tanggal', '>=', Carbon::now()->subDays(45)->toDateString())
                         ->orderBy('tanggal', 'desc')
                         ->paginate(10);

        $data = ['menu' => 'Absensi'];

        // Mengirimkan ID user yang login ke view
        return view('karyawan.absensi', compact('users', 'absensi', 'data', 'loggedInUserId', 'selectedUserId'));
    }

    /**
     * Method terpusat untuk memproses semua jenis absensi.
     */
    public function process(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'action_type' => ['required', Rule::in(['kehadiran', 'status'])],
            'status' => ['nullable', Rule::in(['Sakit', 'Izin'])],
        ]);

        $actionType = $request->input('action_type');
        $id_karyawan = $request->input('id_karyawan');
        $tanggal = $request->input('tanggal');

        $existingAbsensi = Absensi::where('id_karyawan', $id_karyawan)
                                   ->where('tanggal', $tanggal)
                                   ->first();

        if ($actionType === 'status') {
            if ($existingAbsensi) {
                return back()->with('error', 'Karyawan sudah memiliki data absensi pada tanggal tersebut.');
            }
            return $this->handleSakitIzin($id_karyawan, $tanggal, $request->input('status'));
        }

        if ($actionType === 'kehadiran') {
            if ($existingAbsensi && in_array($existingAbsensi->keterangan, ['Sakit', 'Izin'])) {
                return back()->with('error', 'Karyawan sudah ditandai ' . $existingAbsensi->keterangan . ' untuk hari ini.');
            }
            if (!$existingAbsensi) {
                return $this->handleHadir($id_karyawan, $tanggal);
            }
            if ($existingAbsensi && is_null($existingAbsensi->jam_keluar)) {
                return $this->handlePulang($existingAbsensi);
            }
            return back()->with('error', 'Karyawan ini sudah selesai absen untuk hari yang dipilih.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    private function handleHadir($id_karyawan, $tanggal)
    {
        $timezone = 'Asia/Jakarta';
        $currentTime = now($timezone);
        $batasWaktuMasuk = Carbon::parse($tanggal . ' 09:15:00', $timezone);

        Absensi::create([
            'id_karyawan' => $id_karyawan,
            'tanggal' => $tanggal,
            'jam_masuk' => $currentTime->format('H:i:s'),
            'kehadiran' => null,
            'keterangan' => $currentTime->gt($batasWaktuMasuk) ? 'Terlambat' : null,
        ]);
        $redirectRoute = Auth::user()->id_hakakses == 1 ? 'absensi.index.admin' : 'karyawan.absensi.index';
        return redirect()->route($redirectRoute)->with('success', 'Berhasil melakukan absensi masuk.');
    }

    private function handlePulang(Absensi $absensi)
    {
        $timezone = 'Asia/Jakarta';
        $currentTime = now($timezone);
        $batasWaktuLembur = Carbon::parse($absensi->tanggal . ' 17:30:00', $timezone);

        $absensi->jam_keluar = $currentTime->format('H:i:s');
        $absensi->kehadiran = 1;

        $keterangan = [];
        if (str_contains($absensi->keterangan ?? '', 'Terlambat')) {
            $keterangan[] = 'Terlambat';
        }
        if ($currentTime->gt($batasWaktuLembur)) {
            $keterangan[] = 'Lembur';
        }

        $absensi->keterangan = !empty($keterangan) ? implode(', ', $keterangan) : null;
        $absensi->save();
        $redirectRoute = Auth::user()->id_hakakses == 1 ? 'absensi.index.admin' : 'karyawan.absensi.index';
        return redirect()->route($redirectRoute)->with('success', 'Berhasil melakukan absensi pulang.');
    }

    private function handleSakitIzin($id_karyawan, $tanggal, $status)
    {
        Absensi::create([
            'id_karyawan' => $id_karyawan,
            'tanggal' => $tanggal,
            'kehadiran' => 0,
            'keterangan' => $status,
        ]);
        $redirectRoute = Auth::user()->id_hakakses == 1 ? 'absensi.index.admin' : 'absensi.index';
        return redirect()->route($redirectRoute)->with('success', 'Status ' . $status . ' berhasil dicatat.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:absensi,id',
            'tanggal' => 'required|date',
            'edit_status' => ['nullable', Rule::in(['Sakit', 'Izin'])],
            'jam_masuk' => 'nullable',
            'jam_keluar' => 'nullable',
            'admin_password' => 'required',
        ]);

        if (!Hash::check($request->admin_password, Auth::user()->password)) {
            return redirect()->back()->withErrors(['admin_password' => 'Password admin salah.']);
        }

        $absensi = Absensi::findOrFail($request->id);
        $status = $request->edit_status;

        if ($status === 'Sakit' || $status === 'Izin') {
            $absensi->update([
                'tanggal' => $request->tanggal,
                'kehadiran' => 0,
                'keterangan' => $status,
                'jam_masuk' => null,
                'jam_keluar' => null,
            ]);
        } else {
            $timezone = 'Asia/Jakarta';
            $keteranganFinal = [];

            if($request->filled('jam_masuk')){
                $jamMasukInput = Carbon::parse($request->tanggal . ' ' . $request->jam_masuk, $timezone);
                $batasWaktuMasuk = Carbon::parse($request->tanggal . ' 09:15:00', $timezone);
                if ($jamMasukInput->gt($batasWaktuMasuk)) {
                    $keteranganFinal[] = 'Terlambat';
                }
            }

            $kehadiran = null;
            if ($request->filled('jam_keluar')) {
                $kehadiran = 1;
                $jamKeluarInput = Carbon::parse($request->tanggal . ' ' . $request->jam_keluar, $timezone);
                $batasWaktuLembur = Carbon::parse($request->tanggal . ' 17:30:00', $timezone);
                if ($jamKeluarInput->gt($batasWaktuLembur)) {
                    $keteranganFinal[] = 'Lembur';
                }
            }

            $absensi->update([
                'tanggal' => $request->tanggal,
                'jam_masuk' => $request->jam_masuk,
                'jam_keluar' => $request->jam_keluar,
                'kehadiran' => $kehadiran,
                'keterangan' => !empty($keteranganFinal) ? implode(', ', $keteranganFinal) : null,
            ]);
        }

        $redirectRoute = Auth::user()->id_hakakses == 1 ? 'absensi.index.admin' : 'absensi.index';
        return redirect()->route($redirectRoute)->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function cekStatusAbsensi(User $user, $tanggal)
    {
        $absensi = Absensi::where('id_karyawan', $user->id)
                           ->where('tanggal', 'like', $tanggal . '%')
                           ->first();
        if (!$absensi) {
            return response()->json(['text' => 'Proses Absen Masuk', 'disabled' => false]);
        }
        if (in_array($absensi->keterangan, ['Sakit', 'Izin'])) {
            return response()->json(['text' => 'Status: ' . $absensi->keterangan, 'disabled' => true]);
        }
        if (is_null($absensi->jam_keluar)) {
            return response()->json(['text' => 'Proses Absen Pulang', 'disabled' => false]);
        }
        return response()->json(['text' => 'Sudah Selesai Absen', 'disabled' => true]);
    }
}
