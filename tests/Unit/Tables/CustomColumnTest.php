<?php

declare(strict_types=1);

use SaddlePHP\Tables\Columns\CustomColumn;
use Workbench\App\Models\Horse;

it('serializes its element tag and type', function () {
    $payload = CustomColumn::make('breed')->tag('breed-cell')->toArray();

    expect($payload['type'])->toBe('custom')
        ->and($payload['tag'])->toBe('breed-cell');
});

it('refuses to serialize without a tag', function () {
    CustomColumn::make('breed')->toArray();
})->throws(LogicException::class);

it('resolves the raw value', function () {
    $horse = Horse::factory()->create(['breed' => 'mustang']);

    expect(CustomColumn::make('breed')->tag('breed-cell')->resolve($horse))->toBe('mustang');
});
