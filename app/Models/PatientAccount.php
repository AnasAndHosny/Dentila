<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientAccount extends Model
{
    protected $fillable = ['patient_id', 'balance'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function applyTransaction(string $type, int $amount, ?int $treatmentId = null, string $note = null, string $method = 'manual')
    {
        return DB::transaction(function () use ($type, $amount, $treatmentId, $method, $note) {
            if ($type === 'debit') {
                $this->decrement('balance', $amount);
            } else {
                $this->increment('balance', $amount);
            }

            return $this->transactions()->create([
                'type' => $type,
                'amount' => $amount,
                'patient_treatment_id' => $treatmentId,
                'method' => $method,
                'note' => $note,
            ]);
        });
    }
}
