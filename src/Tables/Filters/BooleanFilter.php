<?php

declare(strict_types=1);

namespace SaddlePHP\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

class BooleanFilter extends Filter
{
    protected string $type = 'boolean';

    public function apply(Builder $query, string $value): void
    {
        if ($value === '1' || $value === '0') {
            $query->where($this->name, $value === '1');
        }
    }
}
