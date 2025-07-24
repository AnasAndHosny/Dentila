<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientTreatmentTooth extends Model
{
    protected $fillable = [
        'patient_treatment_id',
        'patient_tooth_id',
    ];

    public function patientTreatment(): BelongsTo
    {
        return $this->belongsTo(PatientTreatment::class);
    }
}
