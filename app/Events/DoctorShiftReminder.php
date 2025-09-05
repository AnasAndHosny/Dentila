<?php

namespace App\Events;

use App\Models\Employee;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DoctorShiftReminder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Employee $doctor;
    public string $day;
    public string $startTime;

    public function __construct(Employee $doctor, string $day, string $startTime)
    {
        $this->doctor = $doctor;
        $this->day = $day;
        $this->startTime = $startTime;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('User.' . $this->doctor->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification';
    }

    public function broadcastWith(): array
    {
        $dayAr = trans($this->day, [], 'ar');

        return [
            'title_en' => 'Shift Reminder',
            'title_ar' => 'تذكير بالدوام',
            'body_en'  => sprintf(
                'Dear Dr. %s, you have a shift on %s at %s',
                $this->doctor->user->name,
                $this->day,
                $this->startTime
            ),
            'body_ar'  => sprintf(
                'دكتور %s، لديك دوام يوم %s الساعة %s',
                $this->doctor->user->name,
                $dayAr,
                $this->startTime
            ),
        ];
    }
}
