<?php

declare(strict_types=1);

namespace RodeoPHP\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends GeneratorCommand
{
    protected $name = 'rodeo:resource';

    protected $description = 'Create a new RodeoPHP resource class';

    protected $type = 'Resource';

    protected function getStub(): string
    {
        return __DIR__.'/stubs/resource.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Rodeo';
    }

    protected function buildClass($name): string
    {
        $model = $this->option('model') ?: $this->guessModel();

        if (! str_contains($model, '\\')) {
            $model = $this->rootNamespace().'Models\\'.$model;
        }

        return str_replace('{{ model }}', '\\'.ltrim($model, '\\'), parent::buildClass($name));
    }

    protected function guessModel(): string
    {
        return Str::beforeLast(class_basename($this->getNameInput()), 'Resource');
    }

    protected function getOptions(): array
    {
        return [
            ['model', null, InputOption::VALUE_OPTIONAL, 'The Eloquent model the resource manages'],
        ];
    }
}
