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
     * Books List Exportion Test
     *
     * @return void
     */

    public function test_multiType_exports()
    {
        $bookModel = new Book;
        $bookTable = $bookModel->getTable();

        $book_attribs = Book::factory(10)->create();

        $id_array = [];
        foreach($book_attribs as $attr) {
            $id_array[] = $attr['id'];
        }
        $fields_attrs = $book_attribs[0]->getAttributes();
        unset($fields_attrs['author_id']);
        $fields_attrs['author'] = NULL;
        $fields = array_keys($fields_attrs);
        $data = [
            'ids' => $id_array,
            'fields' => $fields,
        ];
        $types = config('app.allowed_export_filetypes');
        
        //Check an invalid export type
        $this->post(route('book.export', 'undefined'), $data)
            ->assertSessionHasErrors('export_type');

        //Check an empty id array
        $this->post(route('book.export', 'csv'), ['fields'=>$fields])
            ->assertSessionHasErrors('ids');

        //Check an empty fields array
        $this->post(route('book.export', 'csv'), ['ids'=>$id_array])
            ->assertSessionHasErrors('fields');

        $this->withoutExceptionHandling();
        foreach($types as $type) {
            $res = $this->post(route('book.export', $type), $data)
                ->assertDownload();
        }
    }
}
