<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Workbench\Database\Factories\HorseFactory;

class Horse extends Model
{
    /** @use HasFactory<HorseFactory> */
    use HasFactory;

    protected $fillable = ['name', 'breed', 'notes', 'is_saddled'];

    protected function casts(): array
    {
        return ['is_saddled' => 'boolean'];
    }

    protected static function newFactory(): HorseFactory
    {
        return HorseFactory::new();
    }
}
