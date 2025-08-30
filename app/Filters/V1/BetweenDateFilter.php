<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Support\Str;

class BetweenDateFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $from = $value['from'] ?? now()->toDateString();
        $to   = $value['to'] ?? now()->toDateString();

        $query->whereBetween('start_time', [
            $from . ' 00:00:00',
            $to . ' 23:59:59',
        ]);
    }
}
