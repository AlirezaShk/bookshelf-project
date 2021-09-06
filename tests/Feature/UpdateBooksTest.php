<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

class UpdateBooksTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_edit_single()
    {
        $this->withoutExceptionHandling();
        $bookModel = new Book;
        $bookTable = $bookModel->getTable();

        $book = Book::factory()->create();

        $attributes = Book::factory()->raw();

        //Assert that an error will occur due to incomplete data array
        foreach($attributes as $k => $v) {

            //omit one
            $book->$k = $v;

            //test
            $res = $this->put('/book/'.$book->id, ['fields' => [$k => $v]]);
            $res->assertSessionHasNoErrors();
        }

        //Double checking to be sure
        $this->assertDatabaseHas($bookTable, $attributes);
    }
}
