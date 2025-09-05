<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
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

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        if (!$notifiable->is_verified) {
            return ['database'];
        }

        return ['database', 'whatsapp'];
    }

    /**
     * Store notification in database.
     */
    public function toDatabase(object $notifiable): array
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
            )
        ];
    }

    /**
     * Send notification via WhatsApp.
     */
    public function toWhatsapp(object $notifiable)
    {
        $amount = number_format(abs($this->transaction->amount));
        $type = $this->transaction->type === 'credit' ? 'إيداع' : 'سحب';

        $template = Arr::random(self::$whatsappMessages);

        $message = Str::replace(
            ['{type}', '{amount}', '{note}'],
            [$type, $amount, $this->transaction->note],
            $template
        );

        $delay = rand(5, 10);

        // إرسال الرسالة بعد تأخير
        sleep($delay);

        // تنسيق رقم الهاتف
        $phone = $notifiable->phone_number;
        if (!str_starts_with($phone, '963')) {
            $phone = '963' . ltrim($phone, '0');
        }

        return [
            'phone'   => $phone,
            'message' => $message,
        ];
    }
}
