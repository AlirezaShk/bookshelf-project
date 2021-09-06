<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class BookTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Testing the insertion via factory.
     *
     * @return void
     */
    public function test_methodical_insert()
    {

        $bookModel = new Book;
        $bookTable = $bookModel->getTable();
        $this->withoutExceptionHandling();

        $attributes = Book::factory()->create()->getAttributes();
        $this->assertDatabaseHas($bookTable, $attributes);
    }

    /**
     * Testing the insertion via an array of variables.
     *
     * @return void
     */
    public function test_variable_insert()
    {

        $bookModel = new Book;
        $bookTable = $bookModel->getTable();
        $this->withoutExceptionHandling();
        
        $attributes = [
            'name' => $this->faker->name(),
            'genre' => $this->faker->word(),
            'author_id' => $this->faker->randomNumber(8),
            'descrip' => $this->faker->paragraph(),
            'isbn' => Book::ISBNGenerator(13),
            'release_date' => $this->faker->date('Y-m-d', 'now'),
            'olang' => $this->faker->languageCode(),
            'langs' => json_encode([$this->faker->languageCode()]),
        ];
        
        try {
            Book::create($attributes);
        } catch(\Exception $e) {
            
            $attributes['author_id'] = Author::factory()->create()->getAttribute('id');

            Book::create($attributes);

            $this->assertDatabaseHas($bookTable, $attributes);

            return;
        }

        $this->assertTrue(FALSE);
    }

    public function test_belongs_to_an_author()
    {
        $book = Book::factory()->create();

        $this->assertInstanceOf(Author::class, $book->author);
    }

}
