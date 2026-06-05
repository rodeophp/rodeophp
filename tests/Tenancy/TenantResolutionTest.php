<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;
use Workbench\App\Models\Horse;
use Workbench\App\Models\Ranch;
use Workbench\App\Models\User;

function ranchWithMember(string $name, User $member): Ranch
{
    $ranch = Ranch::factory()->create(['name' => $name]);
    $ranch->users()->attach($member);

    return $ranch;
}

it('lets a member reach their ranch and see only its horses', function () {
    $user = $this->actingAsUser();
    $ranchA = ranchWithMember('Dusty Creek Ranch', $user);
    $ranchB = Ranch::factory()->create(['name' => 'Silver Ridge Ranch']);

    Horse::factory()->create(['name' => 'Cisco', 'ranch_id' => $ranchA->id]);
    Horse::factory()->create(['name' => 'Bandit', 'ranch_id' => $ranchB->id]);

    $this->get("/admin/{$ranchA->getRouteKey()}/resources/horses")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Resources/Index')
            ->count('rows.data', 1)
            ->where('rows.data.0.cells.name', 'Cisco')
        );
});

it('redirects guests to login before any tenant logic runs', function () {
    $ranch = Ranch::factory()->create(['name' => 'Dusty Creek Ranch']);

    // Both real and unknown tenant keys must look identical to a guest, so
    // tenant existence cannot be probed without authenticating first.
    $this->get("/admin/{$ranch->getRouteKey()}/resources/horses")->assertRedirect();
    $this->get('/admin/999999/resources/horses')->assertRedirect();
});

it('forbids a non-member with 403', function () {
    $this->actingAsUser();
    $ranch = Ranch::factory()->create(['name' => 'Foreign Ranch']);

    $this->get("/admin/{$ranch->getRouteKey()}/resources/horses")
        ->assertForbidden();
});

it('404s an unknown tenant key', function () {
    $this->actingAsUser();

    $this->get('/admin/999999/resources/horses')->assertNotFound();
});

it('shares the tenant key in saddle.path', function () {
    $user = $this->actingAsUser();
    $ranch = ranchWithMember('Dusty Creek Ranch', $user);

    $this->get("/admin/{$ranch->getRouteKey()}/resources/horses")
        ->assertInertia(fn (Assert $page) => $page
            ->where('saddle.path', "admin/{$ranch->getRouteKey()}")
            ->where('saddle.tenant.key', $ranch->getRouteKey())
            ->where('saddle.tenant.label', 'Dusty Creek Ranch')
        );
});

it('lists only the user memberships in saddle.tenants', function () {
    $user = $this->actingAsUser();
    $ranchA = ranchWithMember('Dusty Creek Ranch', $user);
    ranchWithMember('Silver Ridge Ranch', $user);
    Ranch::factory()->create(['name' => 'Not Mine Ranch']);

    $this->get("/admin/{$ranchA->getRouteKey()}/resources/horses")
        ->assertInertia(fn (Assert $page) => $page
            ->count('saddle.tenants', 2)
            ->where('saddle.tenants.0.label', 'Dusty Creek Ranch')
            ->where('saddle.tenants.1.label', 'Silver Ridge Ranch')
        );
});
