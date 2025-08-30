<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueTurn extends Model
{
    protected $fillable = [
        'patient_id',
        'employee_id',
        'appointment_id',
        'queue_turn_status_id',
        'arrival_time',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function status()
    {
        return $this->belongsTo(QueueTurnStatus::class, 'queue_turn_status_id');
    }
}
