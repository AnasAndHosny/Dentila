<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class InQueueFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
            $query->inQueue();
        }
    }
}
