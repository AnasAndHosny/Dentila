<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'employee_id',
        'appointment_status_id',
        'start_time',
        'end_time',
    ];

    public function getDurationInMinutesAttribute()
    {
        $start = Carbon::parse($this->start_time);     //هوت كان عاطي أيرور
        $end = Carbon::parse($this->end_time);

        return $start->diffInMinutes($end);
    }

    // تابع يرجع تاريخ الموعد بصيغة YYYY-MM-DD
    public function getDateAttribute()
    {
        return Carbon::parse($this->start_time)->toDateString(); // أو format('Y-m-d')
    }

    // تابع يرجع توقيت بدء الموعد بصيغة HH:MM
    public function getStartTimeOnlyAttribute()
    {
        return Carbon::parse($this->start_time)->format('H:i');
    }

    // تابع يرجع توقيت نهاية الموعد بصيغة HH:MM
    public function getEndTimeOnlyAttribute()
    {
        return Carbon::parse($this->end_time)->format('H:i');
    }


    public function appointmentStatus(): BelongsTo
    {
        return $this->belongsTo(AppointmentStatus::class, 'appointment_status_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->whereHas('appointmentStatus', function ($q) use ($status) {
            $q->where('name', $status);
        });
    }

    public function queueTurn()
    {
        return $this->hasOne(QueueTurn::class);
    }

    public function canDelete()
    {
        if ($this->appointmentStatus->name == 'Pending') return true;
        if ($this->appointmentStatus->name == 'Scheduled' && $this->start_time >= now()->addDay()) return true;
        return false;
    }
}
