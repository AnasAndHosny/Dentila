<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientTreatmentSubstep extends Model
{
    protected $fillable = [
        'patient_treatment_step_id',
        'name',
        'queue',
        'optional',
        'finished',
        'note',
    ];

    public function patientTreatmentStep(): BelongsTo
    {
        return $this->belongsTo(PatientTreatmentStep::class);
    }
}
