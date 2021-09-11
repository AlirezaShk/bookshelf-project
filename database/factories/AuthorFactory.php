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
        $languageCodes = config('app.supporting_languages')['short'];
        return [
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName(),
            'origin' => $this->faker->country(),
            'langs' => [
                $languageCodes[$this->faker->numberBetween(0, count($languageCodes) - 1)],
                $languageCodes[$this->faker->numberBetween(0, count($languageCodes) - 1)],
            ],
            'birth' => $birth,
            'death' => $death,
        ];
    }
}
