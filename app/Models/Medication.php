<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medication extends Model
{
    protected $fillable = [
        'image',
        'name',
        'info',
    ];

    public function medicationPlans(): HasMany
    {
        return $this->hasMany(MedicationPlan::class);
    }
}
