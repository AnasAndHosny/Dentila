<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class HasDueFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        if ($value) {
            $query->hasDue();
        } else {
            $query->clearBalance();
        }
    }
}
