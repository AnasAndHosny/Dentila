<?php

namespace App\Listeners;

use App\Events\DoctorShiftReminder;
use App\Notifications\DoctorShiftReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDoctorShiftReminderNotification implements ShouldQueue
{
    public function handle(DoctorShiftReminder $event): void
    {
        $user = $event->doctor->user;

        if ($user) {
            $user->notify(new DoctorShiftReminderNotification(
                $event->doctor,
                $event->day,
                $event->startTime
            ));
        }
    }
}
