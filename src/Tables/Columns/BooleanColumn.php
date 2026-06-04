<?php

declare(strict_types=1);

namespace SaddlePHP\Tables\Columns;

use Illuminate\Database\Eloquent\Model;

class BooleanColumn extends Column
{
    protected string $type = 'boolean';

    public function resolve(Model $record): mixed
    {
        return (bool) data_get($record, $this->name);
    }
}
