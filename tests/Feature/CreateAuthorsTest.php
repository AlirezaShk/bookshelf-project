<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Author;

class CreateAuthorsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_create_single()
    {
        $authorModel = new Author;
        $authorTable = $authorModel->getTable();

        $birth = $this->faker->date('Y-m-d', 'yesterday');
        $death = $this->faker->optional(0.5, NULL)->dateTimeBetween($birth, 'now');
        if (!is_null($death)) $death = $death->format('Y-m-d');
        $attributes = [
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName(),
            'origin' => $this->faker->country(),
            'langs' => json_encode([$this->faker->languageCode()]),
            'birth' => $birth,
            'death' => $death,
        ];

        //Assert that an error will occur due to incomplete data array
        foreach($attributes as $k => $v) {

            //omit one
            $attributes[$k] = NULL;

            //test
            $res = $this->put('/authors', $attributes);
            if ($k === 'death') $res->assertSessionDoesntHaveErrors($k);
            else $res->assertSessionHasErrors($k);

            //revert
            $attributes[$k] = $v;
        }

        //Double checking to be sure
        unset($attributes['death']);

        $this->assertDatabaseHas($authorTable, $attributes);

        Author::destroy(Author::find($attributes));

        //Assert that proper use will return ok

        $attributes['death'] = $death;

        $res = $this->put('/authors', $attributes);

        $this->assertNotEquals(500, $res->status());

        $res->assertSessionHasNoErrors();

        $this->assertDatabaseHas($authorTable, $attributes);
    }
}
