<?php

declare(strict_types=1);

use RodeoPHP\Rodeo;

it('boots the service provider and resolves the manager', function () {
    expect(app(Rodeo::class))->toBeInstanceOf(Rodeo::class)
        ->and(config('rodeo.path'))->toBe('admin');
});
