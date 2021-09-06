<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;

class ViewBooksTest extends TestCase
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
        $all = Book::factory(10)->create();
        $response = $this->get('/books');
        foreach($all as $record)
            $response->assertSee($record->name);
    }

    public function test_single_view()
    {
        $this->withoutExceptionHandling();
        $record = Book::factory()->create();
        $response = $this->get('/book/' . $record->id)
            ->assertSee($record->name);
    }
}