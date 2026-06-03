<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Gate;
use Inertia\Testing\AssertableInertia as Assert;
use Workbench\App\Models\Horse;
use Workbench\App\Policies\HorsePolicy;

beforeEach(function () {
    Gate::policy(Horse::class, HorsePolicy::class);
    $this->actingAsUser();
});

it('403s create and store when the policy denies', function () {
    $this->get('/admin/resources/horses/create')->assertForbidden();
    $this->post('/admin/resources/horses', ['name' => 'X'])->assertForbidden();
});

it('exposes per-row abilities from the policy', function () {
    Horse::factory()->create(['name' => 'Locked']);

    $this->get('/admin/resources/horses?sort=name&direction=asc')
        ->assertInertia(fn (Assert $page) => $page
            ->where('resource.canCreate', false)
            ->where('rows.data.0.can.update', false)
            ->where('rows.data.0.can.delete', false)
        );
});

it('403s update and destroy when the policy denies', function () {
    $locked = Horse::factory()->create(['name' => 'Locked']);

    $this->put("/admin/resources/horses/{$locked->id}", ['name' => 'Y'])->assertForbidden();
    $this->delete("/admin/resources/horses/{$locked->id}")->assertForbidden();
});
