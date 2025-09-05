<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AppointmentReminder implements ShouldBroadcast
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
}
