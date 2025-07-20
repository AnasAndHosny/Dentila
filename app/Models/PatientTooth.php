<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientTooth extends Model
{
    protected $fillable = [
        'patient_id',
        'tooth_id',
        'tooth_status_id',
        'note',
    ];

    public function tooth(): BelongsTo
    {
        return $this->belongsTo(Tooth::class);
    }
}
