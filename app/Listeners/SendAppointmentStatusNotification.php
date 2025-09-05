<?php

namespace App\Listeners;

use App\Events\AppointmentStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\AppointmentStatusNotification;

class SendAppointmentStatusNotification implements ShouldQueue
{
    public function handle(AppointmentStatusChanged $event): void
    {
        $user = $event->appointment->patient?->user;

        if ($user) {
            $user->notify(new AppointmentStatusNotification($event->appointment));
        }
    }
}
