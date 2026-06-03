<?php

declare(strict_types=1);

namespace RodeoPHP\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string version()
 * @method static string greeting()
 *
 * @see \RodeoPHP\Rodeo
 */
class Rodeo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \RodeoPHP\Rodeo::class;
    }
}
