<?php

declare(strict_types=1);

namespace SaddlePHP\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use SaddlePHP\Fields\Field;

class RecordingField extends Field
{
    protected string $component = 'text-field';

    public ?Model $sawPrototype = null;

    public int $boundCalls = 0;

    public function bound(Model $prototype): void
    {
        $this->sawPrototype = $prototype;
        $this->boundCalls++;
    }
}
