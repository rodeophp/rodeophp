<?php

declare(strict_types=1);

use SaddlePHP\Tables\Filters\BooleanFilter;
use SaddlePHP\Tables\Filters\SelectFilter;
use SaddlePHP\Tables\Table;
use Workbench\App\Models\Horse;

it('serializes select filter definitions', function () {
    $payload = SelectFilter::make('breed')
        ->options(['quarter' => 'Quarter Horse', 'mustang' => 'Mustang'])
        ->toArray();

    expect($payload)->toBe([
        'name' => 'breed',
        'label' => 'Breed',
        'type' => 'select',
        'options' => [
            ['value' => 'quarter', 'label' => 'Quarter Horse'],
            ['value' => 'mustang', 'label' => 'Mustang'],
        ],
    ]);
});

it('serializes boolean filter definitions with a custom label', function () {
    expect(BooleanFilter::make('is_saddled')->label('Saddled')->toArray())->toBe([
        'name' => 'is_saddled',
        'label' => 'Saddled',
        'type' => 'boolean',
    ]);
});

it('applies a declared select value', function () {
    Horse::factory()->create(['breed' => 'quarter']);
    Horse::factory()->create(['breed' => 'mustang']);

    $query = Horse::query();
    SelectFilter::make('breed')->options(['quarter' => 'Quarter Horse'])->apply($query, 'quarter');

    expect($query->pluck('breed')->all())->toBe(['quarter']);
});

it('ignores select values that are not declared options', function () {
    Horse::factory()->count(2)->create();

    $query = Horse::query();
    SelectFilter::make('breed')->options(['quarter' => 'Quarter Horse'])->apply($query, "quarter' OR 1=1");

    expect($query->count())->toBe(2);
});

it('applies boolean filter values', function () {
    Horse::factory()->create(['is_saddled' => true]);
    Horse::factory()->create(['is_saddled' => false]);

    $saddled = Horse::query();
    BooleanFilter::make('is_saddled')->apply($saddled, '1');
    $bare = Horse::query();
    BooleanFilter::make('is_saddled')->apply($bare, '0');
    $junk = Horse::query();
    BooleanFilter::make('is_saddled')->apply($junk, 'yes');

    expect($saddled->count())->toBe(1)
        ->and($bare->count())->toBe(1)
        ->and($junk->count())->toBe(2);
});

it('exposes filters on the table', function () {
    $table = Table::make()->filters([
        $select = SelectFilter::make('breed'),
        BooleanFilter::make('is_saddled'),
    ]);

    expect($table->getFilters())->toHaveCount(2)
        ->and($table->getFilters()[0])->toBe($select)
        ->and($table->filtersToInertia())->toHaveCount(2)
        ->and($table->filtersToInertia()[0]['type'])->toBe('select')
        ->and(Table::make()->filtersToInertia())->toBe([]);
});
