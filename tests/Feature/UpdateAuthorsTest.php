<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Author;

class UpdateAuthorsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Single Author Entry Update Test
     *
     * @return void
     */

    public function test_edit_single()
    {
        $authorModel = new Author;
        $authorTable = $authorModel->getTable();
        while($this->testCnt--) {
            $author = Author::factory()->create();
            $attributes = $author->getAttributes();
            unset($attributes['created_at']);
            unset($attributes['updated_at']);

            $new_attributes = Author::factory()->raw();
            if($attributes['death'] === NULL) {
                $attributes['alive'] = 'yes';
            }

            $attributes['langs'] = json_decode($attributes['langs']);
            //Assert that an error will occur due to incomplete data array
            foreach($new_attributes as $k => $v) {

                $attributes[$k] = $v;
                if($v !== NULL && $k === 'death') {
                    unset($attributes['alive']);
                }
                $res = $this->put(route('author.entry/edit-view', $author->id), $attributes);
                //test
                //If the old obit is prior to birth date, an error should arise in response to our request
                if( $attributes['death'] !== NULL && strtotime($attributes['death']) < strtotime($attributes['birth']))
                    $res->assertSessionHasErrors(['death']);
                else
                    $res->assertSessionHasNoErrors();
            }
            $attributes['langs'] = json_encode($attributes['langs']);
            unset($attributes['alive']);

            //Since SQLite has problems with date fields that are NULL, we unset them here
            if($attributes['death'] === NULL)
                unset($attributes['death']);

            //Double checking to be sure
            $this->assertDatabaseHas($authorTable, $attributes);
        }
    }
}
