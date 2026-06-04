<?php

declare(strict_types=1);

namespace SaddlePHP\Tables\Columns;

class BadgeColumn extends Column
{
    protected string $type = 'badge';

    /** @var array<string, string> value => accent|ink|muted */
    protected array $colors = [];

    /** @param array<string, string> $colors */
    public function colors(array $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    protected function meta(): array
    {
        return ['colors' => $this->colors];
    }
}
