<?php

declare(strict_types=1);

namespace RodeoPHP;

use Illuminate\Support\ServiceProvider;

class RodeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/rodeo.php', 'rodeo');

        $this->app->singleton(Rodeo::class, static fn (): Rodeo => new Rodeo());
        $this->app->alias(Rodeo::class, 'rodeo');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/rodeo.php' => $this->app->configPath('rodeo.php'),
            ], 'rodeo-config');
        }
    }
}
