<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientIntakeMedication extends Model
{
    protected $fillable = [
        'patient_id',
        'intake_medication_id',
    ];
}
