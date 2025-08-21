<?php

namespace App\Notifications;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $balance;

    protected static $whatsappMessages = [
        "لديك رصيد مستحق قدره {amount} لعيادتنا.\nيرجى التسديد في أقرب وقت.",
        "تنويه: يوجد عليك مبلغ {amount} غير مدفوع.\nيرجى مراجعة العيادة للدفع.",
        "رصيدك الحالي هو {amount} بالسالب.\nالرجاء دفع المبلغ لتفادي التأخير.",
        "تذكير ودي 🌟 لديك مبلغ {amount} مستحق.\nنرجو التسديد في أقرب وقت ممكن.",
    ];

    public function __construct(int $balance)
    {
        $this->balance = $balance;
    }

    public function via(object $notifiable): array
    {
        if (!$notifiable->is_verified) {
            return ['database'];
        }

        return ['database', 'whatsapp'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title_en' => 'Payment Reminder',
            'title_ar' => 'تذكير بالدفع',
            'body_en'  => sprintf(
                'Your account has a pending balance of %s. Please pay as soon as possible.',
                number_format(abs($this->balance))
            ),
            'body_ar'  => sprintf(
                'لديك رصيد مستحق قدره %s. يرجى الدفع في أقرب وقت.',
                number_format(abs($this->balance))
            ),
        ];
    }

    public function toWhatsapp(object $notifiable)
    {
        $amount = number_format(abs($this->balance));

        $template = Arr::random(self::$whatsappMessages);

        $message = Str::replace('{amount}', $amount, $template);

        // تأخير عشوائي (5-10 ثواني)
        sleep(rand(5, 10));

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
