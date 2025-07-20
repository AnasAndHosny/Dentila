<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'birthdate',
        'gender',
        'job',
        'marital_status',
        'address',
        'social_history',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birthday' => 'date:Y-m-d',
    ];

    /**
     * Accessor for the birthday attribute.
     */
    protected function birthdate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    /**
     * Interact with the employee's gender.
     */
    protected function gender(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? 'male' : 'female',
            set: fn($value) => $value === 'male'
        );
    }

    // Accessor to calculate age
    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['birthdate'])->age;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function diseases(): BelongsToMany
    {
        return $this->belongsToMany(Disease::class, 'patient_diseases');
    }

    public function intakeMedications(): BelongsToMany
    {
        return $this->belongsToMany(IntakeMedication::class, 'patient_intake_medications');
    }

    public function teeth(): BelongsToMany
    {
        return $this->belongsToMany(Tooth::class, table: 'patient_teeth',)
            ->withPivot('has_treatment', 'note', 'tooth_status_id');
    }

    public function Treatments(): HasMany
    {
        return $this->hasMany(PatientTreatment::class);
    }
}
