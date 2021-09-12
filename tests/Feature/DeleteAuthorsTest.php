<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Author;

class DeleteAuthorsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Author Deletion Test
     *
     * @return void
     */
    public function test_delete_single()
    {
        $this->withoutExceptionHandling();
        $authorModel = new Author;
        $authorTable = $authorModel->getTable();

        while($this->testCnt--) 
        {
            $author = Author::factory()->create();

            $res = $this->delete(route('author.delete', $author->id))
                ->assertOk();

            //Double checking to be sure
            $this->assertDatabaseMissing($authorTable, $author->getAttributes());
        }
    }
}
