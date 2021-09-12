<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

class DeleteBooksTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Book Deletion Test
     *
     * @return void
     */

    public function test_delete_single()
    {
        $this->withoutExceptionHandling();
        $bookModel = new Book;
        $bookTable = $bookModel->getTable();

        while($this->testCnt--) 
        {
            $book = Book::factory()->create();

            $res = $this->delete(route('book.delete', $book->id))
                ->assertOk();

            //Double checking to be sure
            $this->assertDatabaseMissing($bookTable, $book->getAttributes());
        }
    }
}
