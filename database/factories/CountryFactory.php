<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->country(),
            'phone_code' => $this->faker->countryCode(),
            'iso_code' => $this->faker->unique()->countryCode(),
            'currency' => $this->faker->currencyCode(),
            'continent' => $this->faker->randomElement(['Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America']),
            'timezone' => $this->faker->timezone(),
        ];
    }
}
