<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPlan extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'cost',
        'tooth_status_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function toothStatus(): BelongsTo
    {
        return $this->belongsTo(ToothStatus::class);
    }
}
