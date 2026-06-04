<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Workbench\Database\Factories\RiderFactory;

class Rider extends Model
{
    /** @use HasFactory<RiderFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    protected static function newFactory(): RiderFactory
    {
        return RiderFactory::new();
    }
}
