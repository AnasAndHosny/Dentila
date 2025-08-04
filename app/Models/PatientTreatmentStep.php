<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientTreatmentStep extends Model
{
    protected $fillable = [
        'patient_treatment_id',
        'name',
        'queue',
        'optional',
        'treatment_note_id',
        'medication_plan_id',
        'finished',
        'note',
    ];

    public function patientTreatment(): BelongsTo
    {
        return $this->belongsTo(PatientTreatment::class);
    }

    public function treatmentNote(): BelongsTo
    {
        return $this->belongsTo(TreatmentNote::class);
    }

    public function medicationPlan(): BelongsTo
    {
        return $this->belongsTo(MedicationPlan::class);
    }

    public function substeps(): HasMany
    {
        return $this->hasMany(PatientTreatmentSubstep::class)
            ->orderBy('queue')->orderBy('created_at');
    }
}
