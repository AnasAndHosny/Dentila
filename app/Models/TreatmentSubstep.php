<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentSubstep extends Model
{
    protected $fillable = [
        'treatment_step_id',
        'name',
        'queue',
        'optional',
    ];

    public function treatmentStep(): BelongsTo
    {
        return $this->belongsTo(TreatmentStep::class);
    }
}
