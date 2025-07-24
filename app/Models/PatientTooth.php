<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientTooth extends Model
{
    protected $fillable = [
        'patient_id',
        'tooth_id',
        'tooth_status_id',
        'note',
    ];

    /**
     * Accessor to determine if the tooth has an in-progress treatment
     */
    protected function hasTreatment(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->patientTreatmentTooth()
            ->whereHas('patientTreatment', fn($query) => $query->inProgress())
            ->exists()
        );
    }

    public function tooth(): BelongsTo
    {
        return $this->belongsTo(Tooth::class);
    }

    public function patientTreatmentTooth(): HasMany
    {
        return $this->hasMany(PatientTreatmentTooth::class);
    }
}
