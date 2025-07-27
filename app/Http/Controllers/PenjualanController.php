<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPenjualan;
use App\Models\Pemasukan; // <-- Tambahkan model Pemasukan
use App\Models\Penjualan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PenjualanController extends Controller
{
    public function index()
    {
        $data = [
            'menu' => 'Penjualan',
        ];
        return view('karyawan.penjualan', $data);
    }

    /**
     * Menyimpan data penjualan dengan menggunakan Database Transaction.
     */
    public function store(Request $request)
    {
        // Memulai transaksi database
        DB::beginTransaction();
        try {
            // Validasi data yang masuk dari AJAX
            $validated = $request->validate([
                'total_bayar' => 'required|numeric|min:0',
                'detail_penjualan' => 'required|array|min:1',
                'detail_penjualan.*.id_barang' => 'required|exists:barang,id',
                'detail_penjualan.*.qty' => 'required|integer|min:1',
                'detail_penjualan.*.subtotal' => 'required|numeric|min:0',
            ]);

            // 1. Generate ID Transaksi di sisi server untuk keamanan
            $transactionId = 'TRS' . now()->format('Ymd') . Str::upper(Str::random(5));

            // 2. Simpan data ke tabel 'penjualan'
            $penjualan = Penjualan::create([
                'id' => $transactionId,
                'id_karyawan' => auth()->id(),
                'tgl_penjualan' => now(),
                'total_bayar' => $validated['total_bayar'],
            ]);

            $totalNominalTransaksi = 0;

            // 3. Loop untuk menyimpan setiap item ke 'detail_penjualan' dan update stok
            foreach ($validated['detail_penjualan'] as $item) {
                $barang = Barang::find($item['id_barang']);

                // Cek stok barang
                if ($barang->stock < $item['qty']) {
                    // Lemparkan exception dengan pesan yang jelas
                    throw new \Exception('Stok untuk barang ' . $barang->nama . ' tidak mencukupi.');
                }

                // Hitung margin keuntungan
                $margin = ($barang->harga_jual - $barang->harga_modal) * $item['qty'];

                // Menggunakan create() yang lebih bersih setelah model diperbaiki
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id, // ID dari penjualan yang baru dibuat
                    'id_barang' => $item['id_barang'],
                    'qty' => $item['qty'],
                    'harga' => $barang->harga_jual,
                    'subtotal' => $item['subtotal'],
                    'margin' => $margin,
                ]);

                // Kurangi stok barang
                $barang->decrement('stock', $item['qty']);

                // Akumulasi total untuk tabel transaksi
                $totalNominalTransaksi += $item['subtotal'];
            }

            // 4. Simpan data ke tabel 'pemasukan'
            Pemasukan::create([
                'tanggal' => now(),
                'jumlah' => $validated['total_bayar'],
                'sumber_pemasukan' => 'Penjualan',
                'keterangan' => 'Pemasukan dari transaksi ' . $transactionId,
            ]);

            // 5. Simpan data ke tabel 'transaksi'
            Transaksi::create([
                'id_karyawan' => auth()->id(),
                'no_transaksi' => $transactionId,
                'id_jenis_transaksi' => 1, // Asumsi 1 = Penjualan
                'nominal_transaksi' => $totalNominalTransaksi,
                'tanggal_transaksi' => now(),
            ]);

            // Jika semua proses berhasil, commit transaksi
            DB::commit();

            // Kirim respon sukses ke frontend
            return response()->json([
                'message' => 'Transaksi berhasil disimpan!',
                'kembalian' => $request->input('bayar', 0) - $validated['total_bayar']
            ], 200);

        } catch (ValidationException $e) {
            // Jika terjadi error validasi, batalkan transaksi
            DB::rollBack();
            // Kirim respon error validasi ke frontend
            return response()->json(['error' => $e->validator->errors()->first()], 422);

        } catch (\Exception $e) {
            // Jika terjadi error lain, batalkan semua query yang sudah dijalankan
            DB::rollBack();

            // Catat error ke log untuk debugging
            Log::error('Error saat proses checkout: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            // Kirim respon error spesifik ke frontend dengan status 422 (Unprocessable Entity)
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
