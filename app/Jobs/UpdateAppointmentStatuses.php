<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Models\AppointmentStatus;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAppointmentStatuses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $threshold = Carbon::now()->subMinutes(30);

        $appointments = Appointment::where('end_time', '<=', $threshold)->get();

        foreach ($appointments as $appointment) {
            $statusName = $appointment->appointmentStatus->name;
            $newStatus = null;

            if ($statusName === 'Pending') {
                $newStatus = 'Refused';
            } elseif ($statusName === 'Scheduled') {
                $newStatus = 'No Show';
            } elseif ($statusName === 'In Progress') {
                $newStatus = 'Completed';
            }

            if ($newStatus) {
                $appointment->update([
                    'appointment_status_id' => AppointmentStatus::where('name', $newStatus)->first()->id,
                ]);
            }
        }
    }
}
