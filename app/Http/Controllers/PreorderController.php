<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengeluaran;
use App\Models\Preorder;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PreorderController extends Controller
{
    /**
     * Menampilkan halaman preorder untuk staff.
     */
    public function indexStaff()
    {
        $namaKaryawan = Auth::user()->nama;

        $preordersDalamProses = Preorder::whereIn('status', ['Pending', 'Disetujui'])
            ->pluck('id_barang')
            ->toArray();

        $barangHampirHabis = Barang::where('stock', '<', 5)->get();
        $riwayatPreorder = Preorder::where('nama_karyawan', $namaKaryawan)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.preorder_staff', [
            'menu' => 'Preorder Barang',
            'barangHampirHabis' => $barangHampirHabis,
            'riwayatPreorder' => $riwayatPreorder,
            'preordersDalamProses' => $preordersDalamProses,
        ]);
    }

    /**
     * Menyimpan permintaan preorder baru dari staff.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.jumlah' => 'nullable|integer|min:1',
        ]);

        try {
            $itemDibuat = false;
            foreach ($request->items as $id_barang => $data) {
                if (isset($data['selected']) && isset($data['jumlah']) && $data['jumlah'] > 0) {
                    $barang = Barang::find($id_barang);
                    if ($barang) {
                        Preorder::create([
                            'tanggal' => now(),
                            'nama_karyawan' => Auth::user()->nama,
                            'id_barang' => $barang->id,
                            'nama_barang' => $barang->nama,
                            'jumlah' => $data['jumlah'],
                            'harga_beli' => $barang->harga_modal,
                            'total_harga' => $barang->harga_modal * $data['jumlah'],
                            'status' => 'Pending',
                        ]);
                        $itemDibuat = true;
                    }
                }
            }

            if (!$itemDibuat) {
                return back()->with('error', 'Tidak ada item yang dipilih atau jumlah tidak valid.');
            }

            return back()->with('success', 'Permintaan preorder berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Gagal membuat preorder: ' . $e->getMessage());
            // PERUBAHAN: Mengembalikan pesan error generik
            return back()->with('error', 'Terjadi kesalahan saat membuat permintaan. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan halaman approval preorder untuk admin/manager.
     */
    public function indexAdmin()
    {
        $semuaPreorder = Preorder::orderBy('created_at', 'desc')->get();
        return view('admin.preorder_admin', [
            'menu' => 'Persetujuan Preorder',
            'semuaPreorder' => $semuaPreorder,
        ]);
    }

    /**
     * Mengubah status preorder (Disetujui/Ditolak) oleh admin.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Disetujui,Ditolak']);

        DB::beginTransaction();
        try {
            $preorder = Preorder::findOrFail($id);
            $preorder->status = $request->status;
            $preorder->status_dirubah_oleh = Auth::user()->nama;
            $preorder->save();

            if ($request->status == 'Disetujui') {
                $transactionId = 'PRE' . now()->format('Ymd') . Str::upper(Str::random(5));
                Transaksi::create([
                    'id_karyawan' => Auth::id(),
                    'no_transaksi' => $transactionId,
                    'id_jenis_transaksi' => 4, // 4 = Preorder
                    'nominal_transaksi' => $preorder->total_harga,
                    'tanggal_transaksi' => now(),
                ]);

                Pengeluaran::create([
                    'tanggal' => now(),
                    'jumlah' => $preorder->total_harga,
                    'sumber_pengeluaran' => 'Preorder Barang',
                    'keterangan' => 'Pembelian ' . $preorder->nama_barang . ' (Qty: ' . $preorder->jumlah . ')',
                ]);
            }

            DB::commit();
            return back()->with('success', 'Status preorder berhasil diubah.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update status preorder: ' . $e->getMessage());
            // PERUBAHAN: Mengembalikan pesan error generik
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Menyelesaikan preorder (barang sampai) oleh staff dan mengupdate stok.
     */
    public function selesaikan($id)
    {
        DB::beginTransaction();
        try {
            $preorder = Preorder::where('id', $id)->where('status', 'Disetujui')->firstOrFail();

            $barang = Barang::findOrFail($preorder->id_barang);
            $barang->stock += $preorder->jumlah;
            $barang->save();

            $preorder->status = 'Selesai';
            $preorder->save();

            DB::commit();
            return back()->with('success', 'Preorder telah diselesaikan dan stok berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyelesaikan preorder: ' . $e->getMessage());
            // PERUBAHAN: Mengembalikan pesan error generik
            return back()->with('error', 'Terjadi kesalahan saat menyelesaikan preorder.');
        }
    }
}
