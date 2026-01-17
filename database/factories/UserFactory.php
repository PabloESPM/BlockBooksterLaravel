<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'password' => bcrypt('password'),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male','Female','Other']), // coincidir con migración
            'country_id' => \App\Models\Country::inRandomOrder()->first()->id ?? \App\Models\Country::factory(),
            'type' => 'user', // se puede cambiar a 'admin' o 'worker' en el seeder
        ];
    }
}

