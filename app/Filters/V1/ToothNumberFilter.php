<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ToothNumberFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('patientTeeth.tooth', function ($q) use ($value) {
            $q->where('number', $value);
        });
    }
}
