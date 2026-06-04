<?php

declare(strict_types=1);

namespace SaddlePHP\Tables;

use SaddlePHP\Tables\Columns\Column;
use SaddlePHP\Tables\Filters\Filter;

class Table
{
    /** @var array<int, Column> */
    protected array $columns = [];

    public static function make(): self
    {
        return new self;
    }

    /** @param array<int, Column> $columns */
    public function columns(array $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    /** @return array<int, Column> */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /** @return array<int, string> */
    public function sortableColumns(): array
    {
        return collect($this->columns)->filter->isSortable()->map->name()->values()->all();
    }

    /** @return array<int, string> */
    public function searchableColumns(): array
    {
        return collect($this->columns)->filter->isSearchable()->map->name()->values()->all();
    }

    /** @return array<int, array<string, mixed>> */
    public function toInertia(): array
    {
        return collect($this->columns)->map->toArray()->values()->all();
    }

    /** @var array<int, Filter> */
    protected array $filters = [];

    /** @param array<int, Filter> $filters */
    public function filters(array $filters): static
    {
        $this->filters = $filters;

        return $this;
    }

    /** @return array<int, Filter> */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /** @return array<int, array<string, mixed>> */
    public function filtersToInertia(): array
    {
        return collect($this->filters)->map->toArray()->values()->all();
    }
}
