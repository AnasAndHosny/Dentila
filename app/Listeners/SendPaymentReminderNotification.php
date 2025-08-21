<?php

namespace App\Listeners;

use App\Events\PaymentReminder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\PaymentReminderNotification;

class SendPaymentReminderNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentReminder $event): void
    {
        $user = $event->account->patient?->user;

        if ($user && $user->is_verified) {
            // إرسال Notification (Database + WhatsApp)
            $user->notify(new PaymentReminderNotification($event->account->balance));
        }
    }
}
