<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TransactionCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Transaction $transaction)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('User.' . $this->transaction->account->patient->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification';
    }

    public function broadcastWith(): array
    {
        $typeAr   = $this->transaction->type === 'credit' ? 'إيداع' : 'سحب';
        $typeEn = $this->transaction->type === 'credit' ? 'Deposit' : 'Withdrawal';

        return [
            'title_en' => 'New Transaction',
            'title_ar' => 'معاملة مالية جديدة',
            'body_en'  => sprintf(
                '%s of %s has been made. Current balance: %s',
                $typeEn,
                number_format($this->transaction->amount),
                number_format($this->transaction->account->balance)
            ),
            'body_ar'  => sprintf(
                'تمت عملية %s بقيمة %s. الرصيد الحالي: %s',
                $typeAr,
                number_format($this->transaction->amount),
                number_format($this->transaction->account->balance)
            ),
        ];
    }
}
