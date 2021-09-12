<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;

class ApplyBooksFilterTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Book filter application route and JSON response test.
     *
     * @return void
     */
    public function test_filter_application()
    {
        $this->withoutExceptionHandling();
        $filters = array_map(function($a) {
            return $a['code'];
        }, Book::SEARCH_FILTERS);
        while($this->testCnt--) {
            $data = [
                'filterType' => $filters[$this->faker->numberBetween(0, count($filters) - 1)],
                'filterData' => $this->faker->word
            ];

            $res = $this->put(route('api.book.apply-filter'), $data)
                ->assertOk()
                ->assertSessionHasNoErrors()
                ->assertHeader('Content-Type', 'application/json')
                ->assertJson(['success'=>TRUE]);
        }
    }
}
