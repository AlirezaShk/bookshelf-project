<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;

class BooksTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_view()
    {
        $this->withoutExceptionHandling();
        $all = Book::factory(10)->create();
        $response = $this->get('/books');
        foreach($all as $record)
            $response->assertSee($record->name);
    }

    public function test_single_view()
    {
        $this->withoutExceptionHandling();
        $record = Book::factory()->create();
        $response = $this->get('/book/' . $record->id)
            ->assertSee($record->name);
    }

    public function test_create_new()
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
            'descrip' => $this->faker->paragraph(),
        ];

        //Assert that an error will occur due to FOREIGN KEY Constraint

        $this->put('/books', $attributes)
            ->assertStatus(500);

        $this->assertDatabaseMissing($bookTable, $attributes);

        //Assert that proper use will return ok

        $attributes['author_id'] = Author::factory()->create()->id;

        $res = $this->put('/books', $attributes);

        $this->assertNotEquals(500, $res->status());

        $res->assertRedirect('/books');

        $this->assertDatabaseHas($bookTable, $attributes);
    }
}
