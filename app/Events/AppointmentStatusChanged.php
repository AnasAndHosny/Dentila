<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AppointmentStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('User.' . $this->appointment->patient->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification';
    }

    public function broadcastWith(): array
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


        return [
            'title_en' => 'Appointment Status Changed',
            'title_ar' => 'تغيير حالة الموعد',
            'body_en'  => sprintf(
                'Your appointment is now %s on %s (%s) at %s',
                $statusEn,
                $date,
                $dayNameEn,
                $startTime
            ),
            'body_ar'  => sprintf(
                'حالة موعدك الآن %s بتاريخ %s (%s) الساعة %s',
                $statusAr,
                $date,
                $dayNameAr,
                $startTime
            ),
        ];
    }
}
