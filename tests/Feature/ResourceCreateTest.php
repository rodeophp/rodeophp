<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;
use Workbench\App\Models\Horse;

it('renders the create form payload', function () {
    $this->actingAsUser();

    $this->get('/admin/resources/horses/create')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Resources/Create')
            ->where('resource.uriKey', 'horses')
            ->count('fields', 7)
            ->where('fields.0.component', 'text-field')
            ->where('fields.0.name', 'name')
        );
});

it('stores a record, flashes success and redirects to the index', function () {
    $this->actingAsUser();

    $this->post('/admin/resources/horses', [
        'name' => 'Cisco', 'breed' => 'quarter', 'notes' => 'fast', 'is_saddled' => true,
    ])
        ->assertRedirect('/admin/resources/horses')
        ->assertSessionHas('success', 'Horse created.');

    expect(Horse::query()->where('name', 'Cisco')->where('is_saddled', true)->exists())->toBeTrue();
});

it('rejects invalid payloads with validation errors', function () {
    $this->actingAsUser();

    $this->from('/admin/resources/horses/create')
        ->post('/admin/resources/horses', ['name' => '', 'breed' => 'unicorn'])
        ->assertRedirect('/admin/resources/horses/create')
        ->assertSessionHasErrors(['name', 'breed']);

    expect(Horse::query()->count())->toBe(0);
});
