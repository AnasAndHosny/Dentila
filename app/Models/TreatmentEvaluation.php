<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentEvaluation extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'patient_treatment_id',
        'rating',
        'comment',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'doctor_id'); // أو User
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(PatientTreatment::class, 'patient_treatment_id');
    }

    public function scopePending($q)
    {
        return $q->whereNull('rating')->whereDate('created_at', '<=', Carbon::now()->subMonth());
    }

    public function scopeCompleted($q)
    {
        return $q->whereNotNull('rating');
    }

    public function scopeDue($q)
    {
        return $q->whereNull('rating')->whereBetween('created_at', [now()->subMonth()->startOfDay(), now()->subMonth()->endOfDay()]);
    }
}
