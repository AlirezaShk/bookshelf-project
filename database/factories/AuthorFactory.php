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
        $birth = $this->faker->date('Y-m-d', 'yesterday');
        $death = $this->faker->optional(0.5, NULL)->dateTimeBetween($birth, 'now');
        if (!is_null($death)) $death = $death->format('Y-m-d');

        return [
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName(),
            'origin' => $this->faker->country(),
            'langs' => json_encode([$this->faker->languageCode()]),
            'birth' => $birth,
            'death' => $death,
        ];
    }
}
