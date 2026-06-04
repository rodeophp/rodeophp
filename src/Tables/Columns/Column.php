<?php

declare(strict_types=1);

namespace SaddlePHP\Tables\Columns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class Column
{
    protected ?string $label = null;

    protected bool $sortable = false;

    protected bool $searchable = false;

    protected string $type = 'text';

    final public function __construct(protected string $name) {}

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function sortable(bool $sortable = true): static
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function searchable(bool $searchable = true): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function resolve(Model $record): mixed
    {
        return data_get($record, $this->name);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_merge([
            'name' => $this->name,
            'label' => $this->label ?? Str::headline($this->name),
            'sortable' => $this->sortable,
            'type' => $this->type,
        ], $this->meta());
    }

    /** @return array<string, mixed> */
    protected function meta(): array
    {
        return [];
    }
}
