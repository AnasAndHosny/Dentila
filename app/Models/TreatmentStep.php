<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentStep extends Model
{
    protected $fillable = [
        'treatment_plan_id',
        'name',
        'queue',
        'optional',
        'treatment_note_id',
        'medication_plan_id',
    ];

    public function treatmentPlan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class);
    }

    public function treatmentNote(): BelongsTo
    {
        return $this->belongsTo(TreatmentNote::class);
    }

    public function medicationPlan(): BelongsTo
    {
        return $this->belongsTo(MedicationPlan::class);
    }
}
