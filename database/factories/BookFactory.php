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
        return [
            'name' => $this->faker->name(),
            'genre' => $this->faker->word(),
            'release_date' => $this->faker->date('Y-m-d', 'now'),
            'author_id' => function () {
                return Author::factory()->create()->getAttribute('id');
            },
            'isbn' => Book::ISBNGenerator([10,13][$this->faker->numberBetween(0,1)]),
            'olang' => $this->faker->languageCode(),
            'langs' => json_encode([$this->faker->languageCode()]),
            'descrip' => $this->faker->paragraph(),
        ];
    }
}
