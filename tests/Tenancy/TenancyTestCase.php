<?php

declare(strict_types=1);

namespace SaddlePHP\Tests\Tenancy;

use SaddlePHP\Tests\TestCase;
use Workbench\App\Models\Ranch;
use Workbench\App\Saddle\HorseResource;

/**
 * Boots the panel with tenancy ON. The Ranch model is the tenant; the
 * {tenant} route prefix is registered at provider boot from config, so it
 * must be set in defineEnvironment (pre-boot). HorseResource gains its
 * $tenant relationship per test and is restored in tearDown so statics never
 * leak into the tenancy-off suites.
 */
abstract class TenancyTestCase extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('saddle.tenancy.model', Ranch::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        HorseResource::$tenant = 'ranch';
    }

    protected function tearDown(): void
    {
        HorseResource::$tenant = null;

        parent::tearDown();
    }
}
