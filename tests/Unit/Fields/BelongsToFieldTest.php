<?php

declare(strict_types=1);

use Illuminate\Validation\Rules\Exists;
use SaddlePHP\Fields\BelongsTo;
use Workbench\App\Models\Horse;
use Workbench\App\Models\Rider;

it('derives the foreign key, label and exists rule from the relation', function () {
    $field = BelongsTo::make('rider');
    $field->bound(new Horse);

    expect($field->name())->toBe('rider_id')
        ->and($field->toArray()['label'])->toBe('Rider')
        ->and($field->getRules()[0])->toBe('nullable')
        ->and($field->getRules()[1])->toBeInstanceOf(Exists::class);
});

it('builds options from the related resource title, ordered and capped', function () {
    Rider::factory()->create(['name' => 'Cassidy']);
    Rider::factory()->create(['name' => 'Amos']);
    Rider::factory()->create(['name' => 'Billie']);

    $field = BelongsTo::make('rider')->limit(2);
    $field->bound(new Horse);

    $options = $field->toArray()['options'];

    expect($options)->toHaveCount(2)
        ->and($options[0]['label'])->toBe('Amos')
        ->and($options[1]['label'])->toBe('Billie')
        ->and($options[0])->toHaveKeys(['value', 'label']);
});

it('honors an explicit title attribute', function () {
    Rider::factory()->create(['name' => 'Tex']);

    $field = BelongsTo::make('rider')->titleAttribute('id');
    $field->bound(new Horse);

    expect((string) $field->toArray()['options'][0]['label'])->toBe((string) Rider::query()->first()->id);
});

it('rejects names that are not belongs-to relations', function () {
    $missing = BelongsTo::make('nope');
    expect(fn () => $missing->bound(new Horse))->toThrow(LogicException::class);

    $wrongType = BelongsTo::make('newQuery');
    expect(fn () => $wrongType->bound(new Horse))->toThrow(LogicException::class);
});

it('serializes as a select-field component', function () {
    $field = BelongsTo::make('rider');
    $field->bound(new Horse);

    expect($field->toArray()['component'])->toBe('select-field');
});
