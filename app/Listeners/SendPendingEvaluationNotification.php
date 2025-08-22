<?php

namespace App\Listeners;

use App\Events\EvaluationReminder;
use App\Notifications\PendingEvaluationNotification;

class SendPendingEvaluationNotification
{
    public function handle(EvaluationReminder $event): void
    {
        $user = $event->evaluation->patient?->user;

        if ($user) {
            $user->notify(new PendingEvaluationNotification($event->evaluation));
        }
    }
}
