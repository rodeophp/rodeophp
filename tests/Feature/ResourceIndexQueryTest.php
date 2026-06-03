<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;
use Workbench\App\Models\Horse;

beforeEach(function () {
    $this->actingAsUser();
    Horse::factory()->create(['name' => 'Bandit', 'breed' => 'mustang']);
    Horse::factory()->create(['name' => 'Cisco', 'breed' => 'quarter']);
    Horse::factory()->create(['name' => 'Willow', 'breed' => 'appaloosa']);
});

it('filters via search across searchable columns', function () {
    $this->get('/admin/resources/horses?search=cis')
        ->assertInertia(fn (Assert $page) => $page
            ->count('rows.data', 1)
            ->where('rows.data.0.cells.name', 'Cisco')
            ->where('query.search', 'cis')
        );
});

it('sorts by a sortable column ascending and descending', function () {
    $this->get('/admin/resources/horses?sort=name&direction=asc')
        ->assertInertia(fn (Assert $page) => $page->where('rows.data.0.cells.name', 'Bandit'));

    $this->get('/admin/resources/horses?sort=name&direction=desc')
        ->assertInertia(fn (Assert $page) => $page->where('rows.data.0.cells.name', 'Willow'));
});

it('falls back to key desc for non-sortable sort params', function () {
    $this->get('/admin/resources/horses?sort=notes')
        ->assertInertia(fn (Assert $page) => $page
            ->where('query.sort', 'id')
            ->where('query.direction', 'desc')
            ->where('rows.data.0.cells.name', 'Willow')
        );
});
