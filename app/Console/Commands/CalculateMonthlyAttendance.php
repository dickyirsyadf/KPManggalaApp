<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\DaftarGaji;
use App\Models\Potongan;
use App\Models\Tunjangan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

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

            // Mengambil data potongan dari tabel potongan.
            $potongan = Potongan::first();
            $potongan_telat = $potongan->potongan_terlambat ?? 0;
            $potongan_absen = $potongan->potongan_absensi ?? 0;

            $this->info("Menggunakan Potongan Telat: {$potongan_telat}");
            $this->info("Menggunakan Potongan Absen: {$potongan_absen}");

            // Menghitung hari kerja efektif (Senin-Sabtu)
            $hariKerja = 0;
            $startOfMonth = $periode->copy()->startOfMonth();
            $endOfMonth = $periode->copy()->endOfMonth();

            for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
                if (!$date->isSunday()) {
                    $hariKerja++;
                }
            }

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
                $jml_hadir_aktual = $absensiData->where('kehadiran', 1)->count();
                $jml_sakit = $absensiData->where('keterangan', 'Sakit')->count();
                $jml_izin = $absensiData->where('keterangan', 'Izin')->count();

                $jml_terlambat = $absensiData->filter(fn($item) => str_contains($item->keterangan ?? '', 'Terlambat'))->count();
                $jml_lembur = $absensiData->filter(fn($item) => str_contains($item->keterangan ?? '', 'Lembur'))->count();

                // --- LOGIKA PERHITUNGAN ABSEN YANG BARU ---
                // Jumlah absen adalah hari kerja dikurangi hari hadir, sakit, dan semua hari izin.
                $jml_absen = max(0, $hariKerja - ($jml_hadir_aktual + $jml_sakit + $jml_izin));
                // --- AKHIR LOGIKA BARU ---

                // 3. Ambil data user, jabatan, dan tunjangan terkait
                $user = User::with('jabatan')->find($gaji->id_karyawan);
                if (!$user || !$user->jabatan) {
                    Log::warning("Karyawan dengan ID {$gaji->id_karyawan} tidak ditemukan atau tidak memiliki jabatan.");
                    continue;
                }

                $tunjangan = Tunjangan::where('id_jabatan', $user->jabatan->id)->first();
                $rate_lembur = $tunjangan->rate_lembur ?? 0;

                // 4. Update record di tabel daftar_gaji
                $gaji->update([
                    'jml_hr_kerja' => $hariKerja,
                    'jml_hadir' => $jml_hadir_aktual,
                    'jml_absen' => $jml_absen,
                    'jml_terlambat' => $jml_terlambat,
                    'jml_lembur' => $jml_lembur,
                    'jml_izin' => $jml_izin,
                    'jml_sakit' => $jml_sakit,
                ]);

                Log::info("Data absensi untuk karyawan ID: {$gaji->id_karyawan} (Absen final: {$jml_absen}) berhasil diupdate.");
            }

            $this->info('Kalkulasi data absensi bulanan selesai dengan sukses!');
            Log::info('Command kalkulasi data absensi bulanan selesai.');

        } catch (Exception $e) {
            Log::error('Error pada command kalkulasi gaji bulanan.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->error('Terjadi error. Silakan periksa log untuk detail.');
        }
    }
}
