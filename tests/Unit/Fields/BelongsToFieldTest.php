<?php

declare(strict_types=1);

use Illuminate\Validation\Rules\Exists;
use SaddlePHP\Fields\BelongsTo;
use Workbench\App\Models\Horse;
use Workbench\App\Models\Rider;
use Workbench\App\Saddle\RiderResource;

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

it('applies the options query hook', function () {
    Rider::factory()->create(['name' => 'Amos']);
    $billie = Rider::factory()->create(['name' => 'Billie']);

    $field = BelongsTo::make('rider')->modifyOptionsQuery(fn ($query) => $query->where('name', 'Billie'));
    $field->bound(new Horse);

    expect($field->toArray()['options'])->toBe([['value' => $billie->id, 'label' => 'Billie']]);
});

it('searches options by the resolved title attribute', function () {
    Rider::factory()->create(['name' => 'Amos']);
    $billie = Rider::factory()->create(['name' => 'Billie']);

    $field = BelongsTo::make('rider');
    $field->bound(new Horse);

    expect($field->searchOptions('bil'))->toBe([['value' => $billie->id, 'label' => 'Billie']])
        ->and($field->searchOptions())->toHaveCount(2);
});

it('caps and hooks searched options', function () {
    Rider::factory()->create(['name' => 'Amos']);
    Rider::factory()->create(['name' => 'Annie']);
    Rider::factory()->create(['name' => 'August']);

    $field = BelongsTo::make('rider')->limit(2);
    $field->bound(new Horse);

    $hooked = BelongsTo::make('rider')->modifyOptionsQuery(fn ($query) => $query->where('name', '!=', 'Amos'));
    $hooked->bound(new Horse);

    expect($field->searchOptions('a'))->toHaveCount(2)
        ->and($hooked->searchOptions('a'))->toHaveCount(2)
        ->and(collect($hooked->searchOptions('a'))->pluck('label')->all())->toBe(['Annie', 'August']);
});

it('searches by exact key when no title attribute resolves', function () {
    $amos = Rider::factory()->create(['name' => 'Amos']);
    Rider::factory()->create(['name' => 'Billie']);

    $original = RiderResource::$title;
    RiderResource::$title = null;

    try {
        $field = BelongsTo::make('rider');
        $field->bound(new Horse);

        expect($field->searchOptions((string) $amos->id))->toBe([
            ['value' => $amos->id, 'label' => (string) $amos->id],
        ])->and($field->searchOptions('999'))->toBe([]);
    } finally {
        RiderResource::$title = $original;
    }
});

it('swaps to the search select component when searchable', function () {
    Rider::factory()->create(['name' => 'Amos']);

    $field = BelongsTo::make('rider')->searchable();
    $field->bound(new Horse);
    $payload = $field->toArray();

    expect($payload['component'])->toBe('search-select-field')
        ->and($payload['async'])->toBeTrue()
        ->and($payload['options'])->toBe([]);
});

it('embeds only the current selection when editing with a searchable field', function () {
    Rider::factory()->create(['name' => 'Amos']);
    $tex = Rider::factory()->create(['name' => 'Tex']);
    $horse = Horse::factory()->create(['rider_id' => $tex->id]);

    $field = BelongsTo::make('rider')->searchable();
    $field->bound(new Horse);

    expect($field->toArray($horse)['options'])->toBe([['value' => $tex->id, 'label' => 'Tex']]);
});

it('keeps the sync select by default', function () {
    Rider::factory()->create(['name' => 'Amos']);

    $field = BelongsTo::make('rider');
    $field->bound(new Horse);
    $payload = $field->toArray();

    expect($payload['component'])->toBe('select-field')
        ->and($payload)->not->toHaveKey('async')
        ->and($payload['options'])->toHaveCount(1);
});
