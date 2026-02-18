<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'phone' => fake()->numerify('##########'),
            'address' => fake()->address(),
            'salary' => fake()->randomFloat(2, 1000, 10000),
            'age' => fake()->numberBetween(18, 70),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'avatar' => fake()->imageUrl(200, 200, 'people'),
            'identification' => fake()->numerify('ID-########'),
        ];
    }
}
