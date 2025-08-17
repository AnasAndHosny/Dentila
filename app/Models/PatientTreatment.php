<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    protected static function booted()
    {
        static::creating(function ($patientTreatment) {
            if (auth()->check()) {
                $patientTreatment->doctor_id = auth()->user()->employee->id;
            }
        });
    }

    // Scope for finished treatments
    public function scopeCompleted($query)
    {
        return $query->where('finished', true);
    }

    // Scope for running (not finished) treatments
    public function scopeInProgress($query)
    {
        return $query->where('finished', false);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'doctor_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(related: PatientTreatmentStep::class)
            ->orderBy('queue')->orderBy('created_ut');
    }

    public function substeps(): HasManyThrough
    {
        return $this->hasManyThrough(PatientTreatmentSubstep::class, PatientTreatmentStep::class);
    }

    public function patientTeeth(): BelongsToMany
    {
        return $this->belongsToMany(PatientTooth::class, 'patient_treatment_teeth');
    }
}
