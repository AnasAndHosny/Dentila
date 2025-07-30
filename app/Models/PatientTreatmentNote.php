<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PatientTreatmentNote extends Model
{
    protected $fillable = [
        'patient_id',
        'title',
        'text',
        'until_date',
    ];

    protected $casts = [
        'until_date' => 'date:Y-m-d',
    ];

    public function getStartsAtAttribute(): string
    {
        return $this->created_at->format('Y-m-d');
    }

    protected function untilDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Scope: Filter active notes
    public function scopeActive($query)
    {
        return $query->where('until_date', '>=', now());
    }

    // Scope: Expired notes
    public function scopeExpired($query)
    {
        return $query->where('until_date', '<', now());
    }
}
