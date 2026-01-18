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
        // Define las opciones de visibilidad disponibles
        $visibilities = ['private', 'public', 'friends'];
        return [
            'name' => $this->faker->sentence(3),      // Nombre de la lista
            'description' => $this->faker->paragraph(), // Descripción opcional
            // Usa faker->randomElement() para elegir uno aleatoriamente
            'visibility' => $this->faker->randomElement($visibilities),
        ];
    }
}

