<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class DoctorShiftReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Employee $doctor;
    protected string $day;
    protected string $startTime;

    public function __construct(Employee $doctor, string $day, string $startTime)
    {
        $this->doctor = $doctor;
        $this->day = $day;
        $this->startTime = $startTime;
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
        $dayAr = trans($this->day, [], 'ar');

        return [
            'title_en' => 'Shift Reminder',
            'title_ar' => 'تذكير بالدوام',
            'body_en'  => sprintf(
                "Dear Dr. %s, you have a shift on %s at %s",
                $this->doctor->user->name,
                $this->day,
                $this->startTime
            ),
            'body_ar'  => sprintf(
                "دكتور %s، لديك دوام يوم %s الساعة %s",
                $this->doctor->user->name,
                $dayAr,
                $this->startTime
            ),
        ];
    }

    public function toWhatsapp(object $notifiable): array
    {
        $dayAr = trans($this->day, [], 'ar');

        $messageEn = sprintf(
            "Dear Dr. %s, you have a shift on %s at %s",
            $this->doctor->user->name,
            $this->day,
            $this->startTime
        );

        $messageAr = sprintf(
            "دكتور %s، لديك دوام يوم %s الساعة %s",
            $this->doctor->user->name,
            $dayAr,
            $this->startTime
        );

        $phone = $notifiable->phone_number;
        if (!str_starts_with($phone, '963')) {
            $phone = '963' . ltrim($phone, '0');
        }

        return [
            'phone'   => $phone,
            'message' => $messageEn . "\n" . $messageAr,
        ];
    }
}
