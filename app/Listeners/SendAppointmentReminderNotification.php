<?php

namespace App\Listeners;

use App\Events\AppointmentReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\AppointmentReminderNotification;

class SendAppointmentReminderNotification implements ShouldQueue
{
    public function handle(AppointmentReminder $event): void
    {
        $user = $event->appointment->patient?->user;

        if ($user) {
            $user->notify(new AppointmentReminderNotification($event->appointment));
        }
    }
}
