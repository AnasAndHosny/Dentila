<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\TransactionNotification;

class SendTransactionNotification
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
    public function handle(TransactionCreated $event): void
    {
        $user = $event->transaction->account->patient?->user;

        if ($user) {
            // إرسال Notification (Database + WhatsApp)
            $user->notify(new TransactionNotification($event->transaction));
        }
    }
}
