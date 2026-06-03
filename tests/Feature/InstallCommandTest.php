<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

afterEach(function () {
    File::delete(config_path('rodeo.php'));
    File::deleteDirectory(app_path('Rodeo'));
    File::deleteDirectory(public_path('vendor/rodeo'));
});

it('publishes config and creates the resources directory', function () {
    $this->artisan('rodeo:install', ['--no-interaction' => true])->assertSuccessful();

    expect(File::exists(config_path('rodeo.php')))->toBeTrue()
        ->and(File::isDirectory(app_path('Rodeo')))->toBeTrue();
});

it('republishes assets on upgrade', function () {
    $this->artisan('rodeo:upgrade')->assertSuccessful();
});
