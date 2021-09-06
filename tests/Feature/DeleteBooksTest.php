<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;

class DeleteBooksTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_delete_single()
    {
        $this->withoutExceptionHandling();
        $bookModel = new Book;
        $bookTable = $bookModel->getTable();

        $book = Book::factory()->create();

        $attributes = $book->getAttributes();

        $this->post('/book/'.$book->id, ['_method' => 'DELETE'])
            ->assertOk();

        //Double checking to be sure
        $this->assertDatabaseMissing($bookTable, $attributes);
    }
}
