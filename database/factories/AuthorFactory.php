<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName(),
            'origin' => $this->faker->country(),
            'langs' => json_encode([$this->faker->languageCode()]),
            'birth' => $this->faker->date('Y-m-d'),
            'death' => $this->faker->optional(0.5, NULL)->date('Y-m-d', 'now'),
        ];
    }
}
