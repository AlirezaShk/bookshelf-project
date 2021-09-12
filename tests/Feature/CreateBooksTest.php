<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;

class CreateBooksTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * Book Creation Test
     *
     * @return void
     */

    public function test_create_single()
    {
        $bookModel = new Book;
        $bookTable = $bookModel->getTable();

        while($this->testCnt--) {
            $attributes = [
                'name' => $this->faker->name(),
                'genre' => $this->faker->word(),
                'release_date' => $this->faker->date('Y-m-d', 'now'),
                'author_id' => $this->faker->randomNumber(9),
                'isbn' => Book::ISBNGenerator([10,13][$this->faker->numberBetween(0,1)]),
                'olang' => $this->faker->languageCode(),
                'langs' => [$this->faker->languageCode()],
                'descrip' => $this->faker->optional(0.5, NULL)->paragraph(),
            ];

            //Assert that an error will occur due to incomplete data array
            foreach($attributes as $k => $v) {

                //omit one
                $attributes[$k] = NULL;

                //test
                $res = $this->put(route('book.create'), $attributes);
                if ($k === 'descrip') $res->assertSessionDoesntHaveErrors($k)->assertSessionHasErrors('author_id');
                else $res->assertSessionHasErrors($k);

                //revert
                $attributes[$k] = $v;
            }

            //Double checking to be sure

            $attributes['langs'] = json_encode($attributes['langs']);
            $this->assertDatabaseMissing($bookTable, $attributes);

            //Assert that proper use will return ok

            $attributes['author_id'] = Author::factory()->create()->id;

            $attributes['langs'] = json_decode($attributes['langs']);
            $res = $this->put(route('book.create'), $attributes);

            $this->assertNotEquals(500, $res->status());
            
            $res->assertSessionHasNoErrors();

            $attributes['langs'] = json_encode($attributes['langs']);
            $this->assertDatabaseHas($bookTable, $attributes);
        }
    }
}
