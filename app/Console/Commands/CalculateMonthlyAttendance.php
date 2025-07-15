<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\DaftarGaji;
use App\Models\User;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CalculateMonthlyAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gaji:calculate-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghitung absensi bulanan dan memperbarui rincian gaji untuk semua karyawan.';

    // Tarif bisa Anda sesuaikan atau pindahkan ke database/config
    const RATE_LEMBUR = 30000;
    const POTONGAN_TELAT = 25000;
    const POTONGAN_ABSEN = 50000; // Potongan untuk tidak masuk tanpa keterangan

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Memulai command kalkulasi gaji bulanan...');
        $this->info('Memulai kalkulasi gaji bulanan...');

        try {
            // Menentukan periode perhitungan (bulan sebelumnya)
            $periode = now('Asia/Jakarta')->subMonth();
            $bulan = $periode->month;
            $tahun = $periode->year;
            $periodeString = $periode->format('Y-m');

            // Menghitung hari kerja efektif (Senin-Sabtu)
            $hariKerja = 0;
            $startOfMonth = $periode->copy()->startOfMonth();
            $endOfMonth = $periode->copy()->endOfMonth();

            for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
                if (!$date->isSunday()) { // Menghitung Senin sampai Sabtu
                    $hariKerja++;
                }
            }

            // Ambil semua karyawan yang sudah ada di daftar gaji
            $daftarKaryawan = DaftarGaji::all();

            if ($daftarKaryawan->isEmpty()) {
                $this->warn('Tidak ada karyawan di dalam daftar gaji untuk diproses.');
                Log::warning('Tidak ada data di daftar_gaji. Command dihentikan.');
                return;
            }

            $this->info("Memproses gaji untuk periode: {$periodeString} dengan {$hariKerja} hari kerja.");

            foreach ($daftarKaryawan as $gaji) {
                // 1. Ambil data absensi karyawan untuk periode tersebut
                $absensiData = Absensi::where('id_karyawan', $gaji->id_karyawan)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->get();

                // 2. Hitung semua komponen kehadiran
                $jml_hadir = $absensiData->where('kehadiran', 1)->count();
                $jml_terlambat = $absensiData->where('keterangan', 'like', '%Terlambat%')->count();
                $jml_lembur = $absensiData->where('keterangan', 'like', '%Lembur%')->count();
                $jml_sakit = $absensiData->where('keterangan', 'Sakit')->count();
                $jml_izin = $absensiData->where('keterangan', 'Izin')->count();

                // Absen = hari kerja - (hadir + sakit + izin)
                $jml_absen = max(0, $hariKerja - ($jml_hadir + $jml_sakit + $jml_izin));

                // 3. Ambil data gaji & tunjangan
                $user = User::with('jabatan')->find($gaji->id_karyawan);
                $gaji_pokok = $gaji->gaji_pokok;
                $tunjangan_jabatan = $user->jabatan->tunjangan_jabatan ?? 0;

                // 4. Kalkulasi pendapatan dan potongan
                $pendapatan_lembur = $jml_lembur * self::RATE_LEMBUR;
                $potongan_telat = $jml_terlambat * self::POTONGAN_TELAT;
                $potongan_absen = $jml_absen * self::POTONGAN_ABSEN;

                // 5. Kalkulasi gaji bersih
                $gaji_bersih = ($gaji_pokok + $tunjangan_jabatan + $pendapatan_lembur) - $potongan_telat - $potongan_absen;

                // 6. Update record di tabel daftar_gaji
                $gaji->update([
                    'periode_gaji' => $periodeString,
                    'tanggal_hitung_gaji' => now('Asia/Jakarta')->toDateString(),
                    'jml_hr_kerja' => $hariKerja,
                    'jml_hadir' => $jml_hadir,
                    'jml_absen' => $jml_absen,
                    'jml_terlambat' => $jml_terlambat,
                    'jml_lembur' => $jml_lembur,
                    'jml_izin' => $jml_izin,
                    'jml_sakit' => $jml_sakit,
                    'gaji_bersih' => $gaji_bersih,
                ]);

                Log::info("Gaji untuk karyawan ID: {$gaji->id_karyawan} pada periode {$periodeString} berhasil diupdate.");
            }

            $this->info('Kalkulasi gaji bulanan selesai dengan sukses!');
            Log::info('Command kalkulasi gaji bulanan selesai.');

        } catch (Exception $e) {
            Log::error('Error pada command kalkulasi gaji bulanan.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->error('Terjadi error. Silakan periksa log untuk detail.');
        }
    }
}
