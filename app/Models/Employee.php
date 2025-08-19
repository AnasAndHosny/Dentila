<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'image',
        'gender',
        'birthdate',
        'ssn',
        'address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birthdate' => 'date:Y-m-d',
    ];

    /**
     * Accessor for the birthday attribute.
     */
    protected function birthdate(): Attribute
    {
        return Attribute::make(
            get: fn($value): string => Carbon::parse($value)->format(format: 'Y-m-d'),
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
    
    public function treatmentEvaluations()
    {
        return $this->hasMany(TreatmentEvaluation::class, 'doctor_id');
    }

    /* متوسط التقييم (يمكن استخدام withAvg عند الجلب) */
    public function getAvgRatingAttribute(): ?float
    {
        return (float) $this->treatmentEvaluations()
            ->completed()
            ->avg('rating');
    }
}
