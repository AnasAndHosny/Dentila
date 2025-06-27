<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientTooth extends Model
{
    protected $fillable = [
        'patient_id',
        'tooth_id',
        'tooth_status_id',
        'note',
    ];
}
