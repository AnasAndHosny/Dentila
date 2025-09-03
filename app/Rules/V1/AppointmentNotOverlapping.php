<?php

namespace App\Rules\V1;

use App\Models\Appointment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AppointmentNotOverlapping implements ValidationRule
{
    protected $employeeId;
    protected $startTime;
    protected $endTime;
    protected $appointmentId; // مفيد عند التحديث لتجاهل الموعد نفسه

    public function __construct($employeeId, $startTime, $endTime, $appointmentId = null)
    {
        $this->employeeId    = $employeeId;
        $this->startTime     = $startTime;
        $this->endTime       = $endTime;
        $this->appointmentId = $appointmentId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Appointment::where('employee_id', $this->employeeId)
            ->whereHas('appointmentStatus', function ($q) {
                $q->where('name', 'Scheduled');
            });

        // استثناء الموعد الحالي في حال التعديل
        if ($this->appointmentId) {
            $query->where('id', '!=', $this->appointmentId);
        }

        // التحقق من التعارض (معادلة صارمة: startA < endB && endA > startB)
        $overlap = $query->where(function ($q) {
            $q->where('start_time', '<', $this->endTime)
                ->where('end_time', '>', $this->startTime);
        })->exists();

        if ($overlap) {
            $fail(__('This appointment overlaps with another appointment for the same doctor.'));
        }
    }
}
