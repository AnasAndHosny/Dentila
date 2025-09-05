<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    public function toWhatsapp(object $notifiable)
    {
        $statusAr = match ($this->appointment->appointmentStatus->name) {
            'Scheduled' => 'مجدول',
            'Refused'   => 'مرفوض',
            'Cancelled' => 'ملغى',
            default     => $this->appointment->appointmentStatus->name
        };

        $statusEn = $this->appointment->appointmentStatus->name;

        $dateTime = \Carbon\Carbon::parse($this->appointment->start_time);
        $date = $dateTime->format('Y-m-d');
        $dayNameAr = $dateTime->locale('ar')->isoFormat('dddd');
        $dayNameEn = $dateTime->locale('en')->isoFormat('dddd');
        $startTime = $dateTime->format('H:i');

        $messageEn = sprintf(
            'Your appointment is now %s on %s (%s) at %s',
            $statusEn,
            $date,
            $dayNameEn,
            $startTime
        );

        $messageAr = sprintf(
            'حالة موعدك الآن %s بتاريخ %s (%s) الساعة %s',
            $statusAr,
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
