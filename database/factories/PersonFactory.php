<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'nickname' => $this->faker->unique()->word(),
            'date_of_birth' => $this->faker->date(),
            'stack' => [
                $this->faker->word(),
                $this->faker->word(),
                $this->faker->word(),
            ],
        ];
    }
}
