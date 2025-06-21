<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationPlan extends Model
{
    protected $fillable = [
        'medication_id',
        'dose',
        'duration_value',
        'duration_unit',
    ];

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }
}
