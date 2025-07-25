<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Support\Str;

class TreatmentCaseFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value === 'completed') {
            $query->completed();
        } elseif ($value === 'in_progress') {
            $query->inProgress();
        }
    }
}
