<?php

declare(strict_types=1);

use Workbench\App\Models\Rider;

it('serves relation options as json', function () {
    $this->actingAsUser();
    Rider::factory()->create(['name' => 'Billie']);
    $amos = Rider::factory()->create(['name' => 'Amos']);

    $this->getJson('/admin/resources/horses/options/rider_id')
        ->assertOk()
        ->assertJsonCount(2, 'options')
        ->assertJsonPath('options.0.value', $amos->id)
        ->assertJsonPath('options.0.label', 'Amos');
});

it('narrows options with a search term', function () {
    $this->actingAsUser();
    Rider::factory()->create(['name' => 'Amos']);
    Rider::factory()->create(['name' => 'Billie']);

    $this->getJson('/admin/resources/horses/options/rider_id?search=bil')
        ->assertOk()
        ->assertJsonCount(1, 'options')
        ->assertJsonPath('options.0.label', 'Billie');
});

it('rejects guests', function () {
    $this->getJson('/admin/resources/horses/options/rider_id')->assertUnauthorized();
});

it('returns 404 for unknown fields', function () {
    $this->actingAsUser();

    $this->getJson('/admin/resources/horses/options/nope')->assertNotFound();
});

it('returns 404 for fields that are not relations', function () {
    $this->actingAsUser();

    $this->getJson('/admin/resources/horses/options/name')->assertNotFound();
});
