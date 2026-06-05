<?php

declare(strict_types=1);

namespace SaddlePHP\Tables\Columns;

use LogicException;

class CustomColumn extends Column
{
    protected string $type = 'custom';

    protected ?string $tag = null;

    /** The custom element tag the panel renders for this cell. */
    public function tag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    /** @return array<string, mixed> */
    protected function meta(): array
    {
        if ($this->tag === null) {
            throw new LogicException(sprintf(
                'CustomColumn [%s] needs a custom element tag. Call tag() when building the column.',
                $this->name(),
            ));
        }

        return ['tag' => $this->tag];
    }
}
