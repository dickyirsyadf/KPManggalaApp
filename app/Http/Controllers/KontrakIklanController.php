<?php

namespace App\Http\Controllers;

use App\Models\KontrakIklan;
use App\Models\Pemasukan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KontrakIklanController extends Controller
{
    /**
     * Menampilkan halaman untuk staff mengajukan kontrak.
     */
    public function indexStaff()
    {
        $riwayatKontrak = KontrakIklan::where('diajukan_oleh', Auth::user()->nama)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.kontrak_staff', [
            'menu' => 'Kontrak Iklan',
            'riwayatKontrak' => $riwayatKontrak,
        ]);
    }

    /**
     * Menyimpan pengajuan kontrak baru dari staff.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_client' => 'required|string|max:255',
            'nama_media' => 'required|string|max:255',
            'biaya_iklan' => 'required|integer|min:0',
            'tanggal_mulai_kontrak' => 'required|date',
        ]);

        try {
            $tglMulai = Carbon::parse($request->tanggal_mulai_kontrak);
            $durasi = $request->durasi ?? 30; // Default durasi 30 hari jika tidak diisi
            $tglSelesai = $tglMulai->copy()->addDays($durasi);

            KontrakIklan::create([
                'id_kontrak' => 'IKL' . now()->format('Ymd') . Str::upper(Str::random(3)),
                'nama_client' => $request->nama_client,
                'nama_media' => $request->nama_media,
                'durasi' => $durasi,
                'biaya_iklan' => $request->biaya_iklan,
                'tanggal_mulai_kontrak' => $tglMulai,
                'tanggal_selesai_kontrak' => $tglSelesai,
                'status' => 'Dalam Pengajuan',
                'diajukan_oleh' => Auth::user()->nama,
            ]);

            return back()->with('success', 'Pengajuan kontrak iklan berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Gagal membuat kontrak iklan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat pengajuan.');
        }
    }

    /**
     * Menampilkan halaman persetujuan untuk admin/manager.
     */
    public function indexAdmin()
    {
        $semuaKontrak = KontrakIklan::orderBy('created_at', 'desc')->get();
        return view('admin.kontrak_admin', [
            'menu' => 'Persetujuan Kontrak',
            'semuaKontrak' => $semuaKontrak,
        ]);
    }

    /**
     * Mengubah status kontrak (Diterima/Ditolak) oleh admin.
     */
    public function updateStatus(Request $request, $id_kontrak)
    {
        $request->validate(['status' => 'required|in:Diterima,Ditolak']);

        DB::beginTransaction();
        try {
            $kontrak = KontrakIklan::findOrFail($id_kontrak);
            $kontrak->status = $request->status;
            $kontrak->dikonfirmasi_oleh = Auth::user()->nama;
            $kontrak->save();

            // Jika status adalah 'Diterima', catat sebagai pemasukan
            if ($request->status == 'Diterima') {
                // 1. Buat entri di tabel transaksi
                Transaksi::create([
                    'id_karyawan' => Auth::id(),
                    'no_transaksi' => $kontrak->id_kontrak,
                    'id_jenis_transaksi' => 1, // Asumsi 1 = Pemasukan
                    'nominal_transaksi' => $kontrak->biaya_iklan,
                    'tanggal_transaksi' => now(),
                ]);

                // 2. Buat entri di tabel pemasukan
                Pemasukan::create([
                    'tanggal' => now(),
                    'jumlah' => $kontrak->biaya_iklan,
                    'sumber_pemasukan' => 'Kontrak Iklan',
                    'keterangan' => 'Kontrak dengan ' . $kontrak->nama_client . ' di media ' . $kontrak->nama_media,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Status kontrak berhasil diubah.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update status kontrak: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan.');
        }
    }

    /**
     * Mengubah status menjadi 'Sedang Tayang' oleh staff.
     */
    public function updateTayang($id_kontrak)
    {
        try {
            $kontrak = KontrakIklan::where('id_kontrak', $id_kontrak)->where('status', 'Diterima')->firstOrFail();
            $kontrak->status = 'Sedang Tayang';
            $kontrak->save();
            return back()->with('success', 'Status kontrak telah diubah menjadi "Sedang Tayang".');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status.');
        }
    }

    /**
     * Menyelesaikan kontrak oleh staff.
     */
    public function selesaikan($id_kontrak)
    {
        try {
            $kontrak = KontrakIklan::where('id_kontrak', $id_kontrak)->where('status', 'Sedang Tayang')->firstOrFail();
            $kontrak->status = 'Kontrak Selesai';
            $kontrak->save();
            return back()->with('success', 'Kontrak telah diselesaikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan kontrak.');
        }
    }
}
