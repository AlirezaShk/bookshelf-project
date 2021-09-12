<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Author;

class ViewAuthorsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * Authors List View Test
     *
     * @return void
     */
    public function test_list_view()
    {
        $this->withoutExceptionHandling();
        $all = Author::factory(10)->create();
        $response = $this->get(route('author.list'));
        foreach($all as $record)
            $response->assertSee($record->id);
    }

    /**
     * Single Author Entry View Test
     *
     * @return void
     */
    public function test_single_view()
    {
        $this->withoutExceptionHandling();
        while($this->testCnt--) {
            $record = Author::factory()->create();
            $response = $this->get(route('author.entry/edit-view', $record->id))
                ->assertSee($record->id);
        }
    }
}
