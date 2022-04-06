<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ucwords($this->faker->words(3, true));

        return [
            'name' => $name,
            'author' => $this->faker->name(),
            'cover_image' => $this->faker->imageUrl(640, 480, 'books', true, $name)
        ];
    }
}
