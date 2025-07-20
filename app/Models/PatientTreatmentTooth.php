<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientTreatmentTooth extends Model
{
    protected $fillable = [
        'patient_treatment_id',
        'patient_tooth_id',
    ];
}
