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
    protected $description = 'Command to calculate monthly attendance and update salaries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Attendance update command started.');

        try {
            $previousMonth = now()->subMonth();
            $daysInMonth = $previousMonth->daysInMonth;

            // Define holidays (dates in 'Y-m-d' format)
            $holidays = [
                "{$previousMonth->year}-{$previousMonth->month}-01", // Example holiday
                "{$previousMonth->year}-{$previousMonth->month}-25", // Example holiday
            ];

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

            // Calculate Sundays and holidays
            $sundays = [];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($previousMonth->year, $previousMonth->month, $day);
                if ($date->isSunday()) {
                    $sundays[] = $date->toDateString();
                }
            }
            $holidayCount = count(array_intersect($holidays, $sundays));

            Log::info('Sundays and holidays calculated.', [
                'sundays' => $sundays,
                'holidays' => $holidays,
                'holidayCount' => $holidayCount,
            ]);

            // Update DaftarGaji records
            foreach ($attendanceCounts as $attendance) {
                // Calculate the total attended days (including Sundays and holidays)
                $attendedDays = $attendance->jumlah_hadir + $holidayCount;

                // Calculate the number of non-attended days
                $jumlah_tidak_hadir = max(0, $daysInMonth - $attendedDays);

                // Fetch gaji_perhari for the employee
                $daftarGaji = DaftarGaji::where('id_karyawan', $attendance->id_karyawan)->first();
                $gajiPerHari = $daftarGaji->gaji_perhari ?? 0;

                // Calculate bonus
                $bonus = $attendedDays == $daysInMonth ? 100000 : 0;

                // Calculate gaji_bersih
                $gajiBersih = ($attendedDays * $gajiPerHari) + $bonus - ($jumlah_tidak_hadir * 100000);

                // Update DaftarGaji record
                DaftarGaji::where('id_karyawan', $attendance->id_karyawan)
                    ->update([
                        'jumlah_hadir' => $attendedDays,
                        'absen' => $jumlah_tidak_hadir,  // Store the non-attended days
                        'bonus' => $bonus,
                        'gaji_bersih' => $gajiBersih,
                    ]);

                Log::info('DaftarGaji updated for employee.', [
                    'id_karyawan' => $attendance->id_karyawan,
                    'jumlah_hadir' => $attendedDays,
                    'absen' => $jumlah_tidak_hadir,
                    'bonus' => $bonus,
                    'gaji_bersih' => $gajiBersih,
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
