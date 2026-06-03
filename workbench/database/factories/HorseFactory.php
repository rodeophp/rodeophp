<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Horse;

class HorseFactory extends Factory
{
    protected $model = Horse::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->firstName(),
            'breed' => fake()->randomElement(['quarter', 'mustang', 'appaloosa']),
            'notes' => fake()->sentence(),
            'is_saddled' => fake()->boolean(),
        ];
    }
}
