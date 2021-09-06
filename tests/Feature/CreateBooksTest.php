<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;

class CreateBooksTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_create_single()
    {
        $bookModel = new Book;
        $bookTable = $bookModel->getTable();

        $attributes = [
            'name' => $this->faker->name(),
            'genre' => $this->faker->word(),
            'release_date' => $this->faker->date('Y-m-d', 'now'),
            'author_id' => $this->faker->randomNumber(9),
            'isbn' => Book::ISBNGenerator([10,13][$this->faker->numberBetween(0,1)]),
            'olang' => $this->faker->languageCode(),
            'langs' => json_encode([$this->faker->languageCode()]),
            'descrip' => $this->faker->optional(0.5, NULL)->paragraph(),
        ];

        //Assert that an error will occur due to incomplete data array
        foreach($attributes as $k => $v) {

            //omit one
            $attributes[$k] = NULL;

            //test
            $res = $this->put('/books', $attributes);
            if ($k === 'descrip') $res->assertSessionDoesntHaveErrors($k)->assertSessionHasErrors('author_id');
            else $res->assertSessionHasErrors($k);

            //revert
            $attributes[$k] = $v;
        }

        //Double checking to be sure
        $this->assertDatabaseMissing($bookTable, $attributes);

        //Assert that proper use will return ok

        $attributes['author_id'] = Author::factory()->create()->id;

        $res = $this->put('/books', $attributes);

        $this->assertNotEquals(500, $res->status());

        $res->assertSessionHasNoErrors();

        $this->assertDatabaseHas($bookTable, $attributes);
    }
}
