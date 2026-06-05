<?php

declare(strict_types=1);

use Workbench\App\Models\Horse;
use Workbench\App\Models\Ranch;
use Workbench\App\Models\Rider;
use Workbench\App\Models\User;

it('relates a user to ranches and a horse/rider to a ranch', function () {
    $ranch = Ranch::factory()->create(['name' => 'Dusty Creek Ranch']);
    $user = User::factory()->create();
    $ranch->users()->attach($user);

    $horse = Horse::factory()->create(['ranch_id' => $ranch->id]);
    $rider = Rider::factory()->create(['ranch_id' => $ranch->id]);

    expect($user->ranches)->toHaveCount(1)
        ->and($user->ranches->first()->is($ranch))->toBeTrue()
        ->and($horse->ranch->is($ranch))->toBeTrue()
        ->and($rider->ranch->is($ranch))->toBeTrue();
});
