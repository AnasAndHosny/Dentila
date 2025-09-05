<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Events\DoctorShiftReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDoctorShiftReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = now()->locale('en')->isoFormat('dddd'); // Monday, Tuesday...
        $doctors = Employee::with('workingHours')->get();

        foreach ($doctors as $doctor) {
            $shift = $doctor->workingHours()
                ->where('day_of_week', $today)
                ->first();

            if ($shift) {
                event(new DoctorShiftReminder(
                    $doctor,
                    $shift->day_of_week,
                    $shift->start_time
                ));
            }
        }
    }
}
