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
        // Definimos los códigos de país que coinciden con la migración
        $countries = ['es','en','fr','de','it','pt','ca','zh','ja','otros'];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'password' => bcrypt('password'),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male','Female','Other']), // coincidir con migración
            'country' => $this->faker->randomElement($countries), // solo países válidos
            'type' => 'user', // se puede cambiar a 'admin' o 'worker' en el seeder
        ];
    }
}

