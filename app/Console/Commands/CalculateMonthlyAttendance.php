<?php

namespace App\Console\Commands;

use App\Models\SlipGaji;
use App\Models\User;
use Illuminate\Console\Command;
use App\Models\Absensi;
use App\Models\DaftarGaji;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class CalculateMonthlyAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-monthly-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Attendance update command started.');

        try {
            $previousMonth = now()->subMonth();
            $daysInMonth = $previousMonth->daysInMonth;

            Log::info('Previous month calculated.', [
                'year' => $previousMonth->year,
                'month' => $previousMonth->month,
                'daysInMonth' => $daysInMonth,
            ]);

            // Fetch attendance counts
            $attendanceCounts = Absensi::select('id_karyawan', DB::raw('COUNT(*) as jumlah_hadir'))
                ->whereYear('tanggal', $previousMonth->year)
                ->whereMonth('tanggal', $previousMonth->month)
                ->where('kehadiran', '1')
                ->groupBy('id_karyawan')
                ->get();

            Log::info('Attendance counts retrieved.', [
                'attendanceCounts' => $attendanceCounts->toArray(),
            ]);

            // Update DaftarGaji records
            foreach ($attendanceCounts as $attendance) {
                $bonus = $attendance->jumlah_hadir == $daysInMonth ? 100000 : 0;

                // Calculate the number of non-attended days
                $jumlah_tidak_hadir = $daysInMonth - $attendance->jumlah_hadir;

                DaftarGaji::where('id_karyawan', $attendance->id_karyawan)
                    ->update([
                        'jumlah_hadir' => $attendance->jumlah_hadir,
                        'absen' => $jumlah_tidak_hadir,  // Store the non-attended days
                        'bonus' => $bonus,
                    ]);

                Log::info('DaftarGaji updated for employee.', [
                    'id_karyawan' => $attendance->id_karyawan,
                    'jumlah_hadir' => $attendance->jumlah_hadir,
                    'absen' => $jumlah_tidak_hadir,
                    'bonus' => $bonus,
                ]);
            }

            $this->info('Attendance data updated successfully!');
            Log::info('Attendance update command completed successfully.');
        } catch (\Exception $e) {
            Log::error('Error in attendance update command.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error('An error occurred while updating attendance data. Check logs for details.');
        }
    }


}
