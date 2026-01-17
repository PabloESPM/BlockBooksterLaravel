<?php

namespace Database\Factories;

use App\Models\FavList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FavList>
 */
class FavListFactory extends Factory
{
    protected $model = FavList::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),      // Nombre de la lista
            'description' => $this->faker->paragraph(), // Descripción opcional
            'description' => $this->faker->paragraph(), // Descripción opcional
        ];
    }
}

