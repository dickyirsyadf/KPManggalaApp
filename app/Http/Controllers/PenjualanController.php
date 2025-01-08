<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Transaksi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import Log facade

class PenjualanController extends Controller
{
    public function index()
    {
        $data = [
            'menu' => 'Penjualan',
        ];


        return view('admin.penjualan', $data);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_karyawan'=> 'required|exists:users,id',
                'total_bayar' => 'required|numeric',
                'bayar' => 'required|numeric',
                'kembalian' => 'required|numeric',
                'detail_penjualan' => 'required|array',
            ]);

            // Generate unique ID for the transaction
            $today = now()->format('Ymd');  // Format date to Ymd (e.g., 20250102)

            // Attempt to find the latest transaction of today, if any
            $lastTransaction = Penjualan::whereDate('tgl_penjualan', now())->latest()->first();

            // Generate a unique transaction ID based on the last transaction's ID or start from 00001
            if ($lastTransaction) {
                // Extract the numeric part of the last transaction ID
                $lastTransactionNumber = (int)substr($lastTransaction->id, -5);
                $newTransactionNumber = $lastTransactionNumber + 1;  // Increment by 1
            } else {
                // No transaction exists today, start from 00001
                $newTransactionNumber = 1;
            }

            // Format the new transaction ID with leading zeros (5 digits)
            $transactionId = 'TRS' . $today . sprintf('%05d', $newTransactionNumber);

            // Check if the generated ID already exists in the database
            while (Penjualan::where('id', $transactionId)->exists()) {
                $newTransactionNumber++;  // Increment the number if the ID already exists
                $transactionId = 'TRS' . $today . sprintf('%05d', $newTransactionNumber);
            }

            // Insert into penjualan table with manually set transaction ID
            $penjualan = Penjualan::create([
                'id' => $transactionId, // Manually set the transaction ID
                'id_karyawan' => auth()->id(),
                'tgl_penjualan' => now(),
                'total_bayar' => $validated['total_bayar'],
            ]);

            // Ensure the penjualan record is saved successfully
            if (!$penjualan->exists) {
                // Log error and return failure response if penjualan wasn't saved
                Log::error('Failed to create penjualan record.');
                return response()->json(['error' => 'Failed to create transaction record.'], 500);
            }

            Log::info('Transaction ID: ' . $penjualan->id);  // Log the transaction ID to ensure it's being created

            // Insert into detail_penjualan and update stock
            $totalNominalTransaksi = 0;
            foreach ($validated['detail_penjualan'] as $item) {
                // Look up the product in the database using its name
                $barang = Obat::where('nama', $item['product'])->first();

                if (!$barang || $barang->stock < $item['qty']) {
                    // If stock is not sufficient, return error response
                    return response()->json(['error' => 'Stock not sufficient for ' . $item['product']], 400);
                }
                // Calculate the margin per unit and total margin
                $marginPerUnit = $barang->harga_jual - $barang->harga_modal;
                $totalMargin = $marginPerUnit * $item['qty'];
                // Insert each item into the detail_penjualan table
                DetailPenjualan::create([
                    'id_penjualan' => $transactionId, // Associate with the correct transaction ID
                    'id_obat' => $barang->id,
                    'qty' => $item['qty'],
                    'harga' => $barang->harga_jual,
                    'subtotal' => $item['subtotal'],
                    'margin' =>$totalMargin,
                ]);
                // Add the item's subtotal to the total nominal transaksi
                $totalNominalTransaksi += $item['subtotal'];
                // Update product stock after the sale
                $barang->update(['stock' => $barang->stock - $item['qty']]);
            }

                // Insert into transaksi table
                Transaksi::create([
                    'id_karyawan' => auth()->id(),
                    'no_transaksi'=> $transactionId,
                    'id_jenis_transaksi' => 1,
                    'nominal_transaksi' => $totalNominalTransaksi,
                    'tanggal_transaksi' => now(),
                ]);

            // Return success message
            return response()->json(['message' => 'Transaction completed successfully!'], 200);
        } catch (\Exception $e) {
            // Log the error with a detailed message


            Log::error('Error processing transaction: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            // Return error response
            return response()->json(['error' => 'An error occurred during the transaction. Please try again later.'], 500);
        }
    }







}
