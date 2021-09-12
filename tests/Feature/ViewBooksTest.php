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
     * Books List View Test
     *
     * @return void
     */
    public function test_list_view()
    {
        $this->withoutExceptionHandling();
        $all = Book::factory(10)->create();
        $response = $this->get(route('book.list'));
        foreach($all as $record)
            $response->assertSee($record->id);
    }

    /**
     * Single Book Entry View Test
     *
     * @return void
     */
    public function test_single_view()
    {
        $this->withoutExceptionHandling();
        while($this->testCnt--) {
            $record = Book::factory()->create();
            $response = $this->get(route('book.entry/edit-view', $record->id))
                ->assertSee($record->id);
        }
    }
}