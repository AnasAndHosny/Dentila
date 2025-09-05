<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
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
        $dateTime  = \Carbon\Carbon::parse($this->appointment->start_time);
        $date      = $dateTime->format('Y-m-d');
        $dayNameAr = $dateTime->locale('ar')->isoFormat('dddd');
        $dayNameEn = $dateTime->locale('en')->isoFormat('dddd');
        $startTime = $dateTime->format('H:i');

        return [
            'title_en' => 'Appointment Reminder',
            'title_ar' => 'تذكير بالموعد',
            'body_en'  => sprintf(
                'Reminder: You have an appointment on %s (%s) at %s',
                $date,
                $dayNameEn,
                $startTime
            ),
            'body_ar'  => sprintf(
                'تذكير: لديك موعد بتاريخ %s (%s) الساعة %s',
                $date,
                $dayNameAr,
                $startTime
            ),
        ];
    }

    public function toWhatsapp(object $notifiable)
    {
        $dateTime  = \Carbon\Carbon::parse($this->appointment->start_time);
        $date      = $dateTime->format('Y-m-d');
        $dayNameAr = $dateTime->locale('ar')->isoFormat('dddd');
        $dayNameEn = $dateTime->locale('en')->isoFormat('dddd');
        $startTime = $dateTime->format('H:i');

        $messageEn = sprintf(
            'Reminder: You have an appointment on %s (%s) at %s',
            $date,
            $dayNameEn,
            $startTime
        );

        $messageAr = sprintf(
            'تذكير: لديك موعد بتاريخ %s (%s) الساعة %s',
            $date,
            $dayNameAr,
            $startTime
        );

        $message = $messageEn . "\n" . $messageAr;

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
