<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'title_' . $this->faker->unique()->numberBetween('1', '10'),
            'desc' => $this->faker->realText('200'),
            'user_id' => $this->faker->numberBetween('1', '5'),

        ];
    }
}
