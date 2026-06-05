<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Ranch;

class RanchFactory extends Factory
{
    protected $model = Ranch::class;

    public function definition(): array
    {
        $adjectives = ['Dusty', 'Silver', 'Golden', 'Lone Star', 'Prairie', 'Sunset', 'Thunder', 'Red River', 'Blue Mesa', 'Painted'];
        $nouns = ['Creek Ranch', 'Ridge Ranch', 'Valley Ranch', 'Bluff Ranch', 'Canyon Ranch', 'Springs Ranch', 'Hollow Ranch', 'Gulch Ranch', 'Mesa Ranch', 'Flats Ranch'];

        return [
            'name' => fake()->randomElement($adjectives).' '.fake()->randomElement($nouns),
        ];
    }
}
