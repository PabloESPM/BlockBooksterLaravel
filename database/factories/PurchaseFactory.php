<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'book_isbn' => Book::inRandomOrder()->value('isbn'),

            'provider' => $this->faker->randomElement([
                'Amazon',
                'Fnac',
                'Casa del Libro',
                'El Corte Inglés'
            ]),

            // ENUM EXACTO
            'format' => $this->faker->randomElement([
                'paperback',
                'hardcover',
                'ebook',
                'audiobook'
            ]),

            'country' => $this->faker->randomElement([
                'es', 'fr', 'de', 'it', 'pt'
            ]),

            'affiliate_url' => $this->faker->url(),

            'active' => $this->faker->boolean(80),
        ];
    }
}


