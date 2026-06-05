<?php

declare(strict_types=1);

namespace SaddlePHP\Fields;

use LogicException;

class CustomField extends Field
{
    protected string $component = 'custom-field';

    protected ?string $tag = null;

    /** The custom element tag the panel renders for this field. */
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
                'CustomField [%s] needs a custom element tag. Call tag() when building the field.',
                $this->name(),
            ));
        }

        return ['tag' => $this->tag];
    }
}
