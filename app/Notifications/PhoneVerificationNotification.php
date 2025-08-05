<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class PhoneVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $otp;
    protected $templates;

    public function __construct()
    {
        $this->otp = new Otp;
        $this->locale = App::getLocale();

        // رسائل تحقق عشوائية بالعربية
        $this->templates = [
            "رمز التحقق الخاص بك هو: :code. لا تشاركه مع أحد.",
            "أدخل هذا الرمز لإكمال عملية التحقق: :code",
            "رمزك السري هو: :code. استخدمه خلال 5 دقائق.",
            "لأسباب أمنية، استخدم هذا الرمز: :code",
            "تحقق من رقمك عبر إدخال الرمز التالي: :code",
        ];
    }

    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    public function toWhatsapp(object $notifiable): array
    {
        // استخدم البريد أو الهاتف كمفتاح للـ OTP
        $otp = $this->otp->generate($notifiable->phone_number . '|verify', 'numeric', 4);

        // اختر رسالة عشوائية
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
