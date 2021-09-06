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
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_view()
    {
        $this->withoutExceptionHandling();
        $all = Author::factory(10)->create();
        $response = $this->get('/authors');
        foreach($all as $record)
            $response->assertSee($record->fname . " " . $record->lname);
    }

    public function test_single_view()
    {
        $this->withoutExceptionHandling();
        $record = Author::factory()->create();
        $response = $this->get('/author/' . $record->id)
            ->assertSee($record->fname . " " . $record->lname);
    }
}
