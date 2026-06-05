<?php

declare(strict_types=1);

namespace SaddlePHP;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SaddlePHP\Console\InstallCommand;
use SaddlePHP\Console\ResourceMakeCommand;
use SaddlePHP\Console\UpgradeCommand;
use SaddlePHP\Http\Middleware\HandleSaddleRequests;
use SaddlePHP\Http\Middleware\ResolveSaddleTenant;

class SaddleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/saddle.php', 'saddle');

        $this->app->singleton(Saddle::class, static fn (): Saddle => new Saddle);
        $this->app->alias(Saddle::class, 'saddle');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'saddle');

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/saddle.php' => $this->app->configPath('saddle.php'),
            ], 'saddle-config');

            $this->publishes([
                __DIR__.'/../dist' => public_path('vendor/saddle'),
            ], 'saddle-assets');

            $this->commands([
                ResourceMakeCommand::class,
                InstallCommand::class,
                UpgradeCommand::class,
            ]);
        }
    }

    protected function registerRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        $tenancyOn = config('saddle.tenancy.model') !== null;

        $prefix = config('saddle.path', 'admin');
        $middleware = config('saddle.middleware', ['web', 'auth']);

        if ($tenancyOn) {
            $prefix .= '/{tenant}';
            // ResolveSaddleTenant binds the tenant before HandleSaddleRequests
            // shares props that depend on it, and enforces membership (403)
            // before anything renders.
            $middleware[] = ResolveSaddleTenant::class;
        }

        $middleware[] = HandleSaddleRequests::class;

        Route::prefix($prefix)
            ->middleware($middleware)
            ->name('saddle.')
            ->group(__DIR__.'/../routes/saddle.php');
    }
}
