<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;

it('keeps the v0.5 saddle prop shape when tenancy is off', function () {
    $this->actingAsUser();

    $this->get('/admin/resources/horses')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('saddle.path', 'admin')
            ->missing('saddle.tenant')
            ->missing('saddle.tenants')
        );
});
