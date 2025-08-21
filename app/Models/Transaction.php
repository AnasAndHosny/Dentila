<?php

namespace App\Models;

use App\Events\TransactionCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'patient_account_id',
        'patient_treatment_id',
        'type',
        'amount',
        'method',
        'note',
    ];

    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (auth()->check()) {
                $transaction->created_by = auth()->user()->employee->id;
            }
        });

        static::created(function ($transaction) {
            // أرسل الإشعار للمريض المرتبط بالحساب
            event(new TransactionCreated($transaction));
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(PatientAccount::class, 'patient_account_id');
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(PatientTreatment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
}
