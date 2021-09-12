<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Author;

class ExportAuthorsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * Authors List Exportion Test
     *
     * @return void
     */

    public function test_multiType_exports()
    {
        $authorModel = new Author;
        $authorTable = $authorModel->getTable();

        $author_attribs = Author::factory(10)->create();

        $id_array = [];
        foreach($author_attribs as $attr) {
            $id_array[] = $attr['id'];
        }
        $fields_attrs = $author_attribs[0]->getAttributes();
        $fields_attrs['books'] = NULL;
        $fields_attrs['name'] = NULL;
        unset($fields_attrs['fname']);
        unset($fields_attrs['lname']);
        $fields = array_keys($fields_attrs);
        $data = [
            'ids' => $id_array,
            'fields' => $fields,
        ];
        $types = config('app.allowed_export_filetypes');
        
        //Check an invalid export type
        $this->post(route('author.export', 'undefined'), $data)
            ->assertSessionHasErrors('export_type');

        //Check an empty id array
        $this->post(route('author.export', 'csv'), ['fields'=>$fields])
            ->assertSessionHasErrors('ids');

        //Check an empty fields array
        $this->post(route('author.export', 'csv'), ['ids'=>$id_array])
            ->assertSessionHasErrors('fields');

        $this->withoutExceptionHandling();
        foreach($types as $type) {
            $res = $this->post(route('author.export', $type), $data)
                ->assertDownload();
        }
    }
}
