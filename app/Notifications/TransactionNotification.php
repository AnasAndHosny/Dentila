<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaction;
    protected static $whatsappMessages = [
        "تم تنفيذ عملية {type} بمبلغ {amount} لسبب:\n{note}.",
        "تم تسجيل {type} بقيمة {amount}.\nالتفاصيل: {note}.",
        "عملية {type} بمقدار {amount} تمت بنجاح.\nسبب العملية: {note}.",
        "لقد أجرينا {type} بمبلغ {amount}.\nالعملية: {note}.",
        "عملية مالية نوعها {type} بقيمة {amount}.\n{note}."
    ];


    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via(object $notifiable): array
    {
        if (!$notifiable->is_verified) {
            return [];
        }

        return ['whatsapp'];
    }

    // public function toDatabase(object $notifiable): array
    // {
    //     return [
    //         'amount' => $this->transaction->amount,
    //         'note' => $this->transaction->note,
    //         'balance' => $this->transaction->account->balance,
    //     ];
    // }

    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     return new BroadcastMessage([
    //         'amount' => $this->transaction->amount,
    //         'note' => $this->transaction->note,
    //         'balance' => $this->transaction->account->balance,
    //     ]);
    // }

    public function toWhatsapp(object $notifiable)
    {
        $amount = number_format(abs($this->transaction->amount));
        $type = $this->transaction->type === 'deposit' ? 'إيداع' : 'سحب';

        $template = Arr::random(self::$whatsappMessages);

        $message = Str::replace(
            ['{type}', '{amount}', '{note}'],
            [$type, $amount, $this->transaction->note],
            $template
        );

        $delay = rand(5, 10);

        // إرسال الرسالة بعد تأخير
        sleep($delay);

        $phone = $notifiable->phone_number;
        if (!str_starts_with($phone, '963')) {
            $phone = '963' . ltrim($phone, '0');
        }

        return [
            'phone' => $phone,
            'message' => $message,
        ];
    }
}
