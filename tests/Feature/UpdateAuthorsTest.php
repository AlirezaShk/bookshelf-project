<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Author;

class UpdateAuthorsTest extends TestCase
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
        $authorModel = new Author;
        $authorTable = $authorModel->getTable();

        $author = Author::factory()->create();

        $attributes = Author::factory()->raw();

        //Assert that an error will occur due to incomplete data array
        foreach($attributes as $k => $v) {

            //omit one
            $author->$k = $v;

            //test
            $res = $this->put('/author/'.$author->id, ['fields' => [$k => $v]]);
            $res->assertSessionHasNoErrors();
        }

        //Double checking to be sure
        $this->assertDatabaseHas($authorTable, $attributes);
    }
}
