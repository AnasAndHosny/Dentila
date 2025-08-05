<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class ResetPasswordWhatsAppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $otp;
    protected $templates;

    public function __construct()
    {
        $this->otp = new Otp;
        $this->locale = App::getLocale();

        $this->templates = [
            "لإعادة تعيين كلمة المرور، استخدم الرمز التالي: :code",
            "رمز استعادة كلمة المرور الخاصة بك هو: :code",
            "إذا لم تطلب إعادة تعيين كلمة المرور، تجاهل هذه الرسالة. الرمز: :code",
            "استخدم هذا الرمز لإعادة تعيين كلمة المرور: :code",
            "أدخل الرمز التالي لاستعادة الوصول إلى حسابك: :code",
        ];
    }

    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    public function toWhatsapp(object $notifiable): array
    {
        // OTP مرتبط بالهاتف فقط
        $otp = $this->otp->generate($notifiable->phone_number . '|reset', 'numeric', 4);

        $template = $this->templates[array_rand($this->templates)];
        $message = str_replace(':code', $otp->token, $template);

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
