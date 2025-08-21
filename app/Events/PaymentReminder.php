<?php

namespace App\Events;

use App\Models\PatientAccount;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaymentReminder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public PatientAccount $account) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('User.' . $this->account->patient->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification';
    }

    public function broadcastWith(): array
    {
        return [
            'title_en' => 'Payment Reminder',
            'title_ar' => 'تذكير بالدفع',
            'body_en'  => sprintf(
                'Your account has a pending balance of %s. Please pay as soon as possible.',
                number_format(abs($this->account->balance))
            ),
            'body_ar'  => sprintf(
                'لديك رصيد مستحق قدره %s. يرجى الدفع في أقرب وقت.',
                number_format(abs($this->account->balance))
            ),
        ];
    }
}
