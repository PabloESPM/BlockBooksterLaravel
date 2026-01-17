<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'isbn' => $this->faker->unique()->isbn13(),
            'title' => $this->faker->sentence(4),
            'publisher' => $this->faker->company(),
            'publication_year' => $this->faker->numberBetween(1950, now()->year),

            // ENUM correcto
            'language' => $this->faker->randomElement([
                'es','en','fr','de','it','pt','ca','zh','ja','otros'
            ]),

            'number_of_pages' => $this->faker->numberBetween(80, 900),

            // No generar imágenes reales para evitar errores de permisos
            'cover_path' => 'covers/' . $this->faker->uuid . '.jpg',

            // ENUM correcto
            'genre' => $this->faker->randomElement([
                'ficcion','no_ficcion','misterio','thriller','romance',
                'fantasia','ciencia_ficcion','terror','biografia',
                'historia','poesia','ensayo','infantil','juvenil','autoayuda'
            ]),

            'description' => $this->faker->paragraphs(3, true),
        ];
    }
}


