<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Rider;

class RiderFactory extends Factory
{
    protected $model = Rider::class;

    public function definition(): array
    {
        return ['name' => fake()->unique()->name()];
    }
}
