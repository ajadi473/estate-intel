<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class booksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'isbn' => $this->faker->randomDigitNotNull(),
            'authors' => $this->faker->name(),
            'country' => $this->faker->country(),
            'number_of_pages' => $this->faker->numberBetween(1,2000),
            'publisher' => $this->faker->name(),
            'release_date' => $this->faker->date(),
        ];
    }
}
