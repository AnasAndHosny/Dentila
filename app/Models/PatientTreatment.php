<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PatientTreatment extends Model
{
    protected $fillable = [
        'patient_id',
        'name',
        'category_id',
        'cost',
        'tooth_status_id',
        'main_complaint',
        'diagnoses',
        'finished',
        'complete_percentage',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(related: PatientTreatmentStep::class)
            ->orderBy('queue')->orderBy('created_ut');
    }

    public function patientTeeth(): BelongsToMany
    {
        return $this->belongsToMany(PatientTooth::class, 'patient_treatment_teeth');
    }
}
