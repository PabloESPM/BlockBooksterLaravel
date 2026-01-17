<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->firstName(),
            'surname'     => $this->faker->lastName(),
            'birth_date'  => $this->faker->date(),
            'country_id'  => \App\Models\Country::inRandomOrder()->first()->id ?? \App\Models\Country::factory(),
            'biography'   => $this->faker->text(),
        ];
    }
}

