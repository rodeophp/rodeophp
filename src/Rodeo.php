<?php

declare(strict_types=1);

namespace RodeoPHP;

/**
 * The RodeoPHP core. The wrangler that panels, resources and plugins
 * will register themselves against as the framework grows.
 */
class Rodeo
{
    public const VERSION = '0.1.0-dev';

    public function version(): string
    {
        return self::VERSION;
    }

    public function greeting(): string
    {
        return "Saddle up, cowboy. There's a new CMF in town.";
    }
}
