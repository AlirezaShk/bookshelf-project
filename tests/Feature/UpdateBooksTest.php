<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

class UpdateBooksTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Single Book Entry Update Test
     *
     * @return void
     */

    public function test_edit_single()
    {
        $bookModel = new Book;
        $bookTable = $bookModel->getTable();
        while($this->testCnt--) {
            $book = Book::factory()->create();
            $attributes = $book->getAttributes();
            unset($attributes['created_at']);
            unset($attributes['updated_at']);

            $new_attributes = Book::factory()->raw();

            $attributes['langs'] = json_decode($attributes['langs']);
            //Assert that an error will occur due to incomplete data array
            foreach($new_attributes as $k => $v) {

                $attributes[$k] = $v;
                $res = $this->put(route('book.entry/edit-view', $book->id), $attributes);
                //test
                $res->assertSessionHasNoErrors();
            }
            $attributes['langs'] = json_encode($attributes['langs']);

            //Double checking to be sure
            $this->assertDatabaseHas($bookTable, $attributes);
        }
    }
}
