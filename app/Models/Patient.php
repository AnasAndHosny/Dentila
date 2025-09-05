<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'birthdate' => 'date:Y-m-d',
        'job' => 'encrypted',
        'marital_status' => 'encrypted',
        'address' => 'encrypted',
        'social_history' => 'encrypted',
        'note' => 'encrypted',
    ];

    protected static function booted()
    {
        static::created(function (Patient $patient) {
            $patient->account()->create();
        });
    }

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

    public function scopeHasDue($query)
    {
        return $query->whereHas('account', function ($q) {
            $q->where('balance', '<', 0);
        });
    }

    public function scopeClearBalance($query)
    {
        return $query->whereHas('account', function ($q) {
            $q->where('balance', '>=', 0);
        });
    }

    public function scopeInQueue($query)
    {
        return $query->whereHas('queueTurns', function ($q) {
            $q->where('queue_turn_status_id', QueueTurnStatus::firstWhere('name', 'Checked In')->id);
        });
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

    public function teeth(): HasMany
    {
        return $this->hasMany(PatientTooth::class);
    }

    public function originalTeeth(): BelongsToMany
    {
        return $this->belongsToMany(Tooth::class, table: 'patient_teeth',)
            ->withPivot('has_treatment', 'note', 'tooth_status_id');
    }

    public function Treatments(): HasMany
    {
        return $this->hasMany(PatientTreatment::class);
    }

    public function treatmentNotes(): HasMany
    {
        return $this->hasMany(PatientTreatmentNote::class);
    }

    public function medicationPlans(): HasMany
    {
        return $this->hasMany(PatientMedicationPlan::class);
    }

    public function account(): HasOne
    {
        return $this->hasOne(PatientAccount::class);
    }

    public function treatmentEvaluations(): HasMany
    {
        return $this->hasMany(TreatmentEvaluation::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function queueTurns()
    {
        return $this->hasMany(QueueTurn::class);
    }
}
