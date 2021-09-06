<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;

class ExportBooksTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_multiType_exports()
    {
        $this->withoutExceptionHandling();
        $_ENV['DB_HOST'] = 'mysql';

        $bookModel = new Book;
        $bookTable = $bookModel->getTable();

        $book_attribs = Book::factory(10)->create();

        $id_array = [];
        foreach($book_attribs as $attr) {
            $id_array[] = $attr['id'];
        }

        $types = config('app.allowed_export_filetypes');
        
        foreach($types as $type) {
            $res = $this->post('/export/'.$type, $id_array)
                ->assertDownload();
        }
    }
}
