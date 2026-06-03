<?php

declare(strict_types=1);

use Workbench\App\Models\Horse;

it('deletes a record and redirects with a flash', function () {
    $this->actingAsUser();
    $horse = Horse::factory()->create();

    $this->delete("/admin/resources/horses/{$horse->id}")
        ->assertRedirect('/admin/resources/horses')
        ->assertSessionHas('success', 'Horse deleted.');

    expect(Horse::query()->find($horse->id))->toBeNull();
});
