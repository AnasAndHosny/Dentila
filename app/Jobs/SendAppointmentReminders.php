<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Events\AppointmentReminder;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAppointmentReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $targetTime = Carbon::now()->addDay()->format('Y-m-d H:i:s');

        $appointments = Appointment::whereHas('appointmentStatus', fn($q) => $q->where('name', 'Scheduled'))
            ->whereBetween('start_time', [Carbon::parse($targetTime)->startOfHour(), Carbon::parse($targetTime)->endOfHour()])
            ->get();

        foreach ($appointments as $appointment) {
            event(new AppointmentReminder($appointment));
        }
    }
}
