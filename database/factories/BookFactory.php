<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $languageCodes = config('app.supporting_languages')['short'];
        return [
            'name' => $this->faker->name(),
            'genre' => $this->faker->word(),
            'release_date' => $this->faker->date('Y-m-d', 'now'),
            'author_id' => function () {
                return Author::factory()->create()->getAttribute('id');
            },
            'isbn' => Book::ISBNGenerator([10,13][$this->faker->numberBetween(0,1)]),
            'olang' => $languageCodes[$this->faker->numberBetween(0, count($languageCodes) - 1)],
            'langs' => [
                $languageCodes[$this->faker->numberBetween(0, count($languageCodes) - 1)],
                $languageCodes[$this->faker->numberBetween(0, count($languageCodes) - 1)],
            ],
            'descrip' => $this->faker->optional(0.5, NULL)->paragraph(),
        ];
    }
}
