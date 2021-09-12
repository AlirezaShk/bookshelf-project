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
     * Author Creation Test
     *
     * @return void
     */

    public function test_create_single()
    {
        $authorModel = new Author;
        $authorTable = $authorModel->getTable();

        while($this->testCnt--) {
            $birth = $this->faker->date('Y-m-d', 'yesterday');
            $death = $this->faker->optional(0.5, NULL)->dateTimeBetween($birth, 'now');

            if (!is_null($death)) $death = $death->format('Y-m-d');
            $attributes = [
                'fname' => $this->faker->firstName(),
                'lname' => $this->faker->lastName(),
                'origin' => $this->faker->country(),
                'langs' => [$this->faker->languageCode()],
                'birth' => $birth,
                'death' => $death,
            ];

            //Assert that an error will occur due to incomplete data array
            foreach($attributes as $k => $v) {

                //omit one
                $attributes[$k] = NULL;
                if ($k === 'death') {
                    $attributes['alive'] = 'yes';
                }
                //test
                $res = $this->put(route('author.create'), $attributes);
                if ($k === 'death') {
                    $res->assertSessionDoesntHaveErrors($k);
                    unset($attributes['alive']);
                }
                else $res->assertSessionHasErrors($k);

                //revert
                $attributes[$k] = $v;
            }

            $attributes['death'] = NULL;
            $attributes['langs'] = json_encode($attributes['langs']);
            //Double checking to be sure
            $this->assertDatabaseHas($authorTable, $attributes);

            Author::destroy(Author::find($attributes));

            //Assert that proper use will return ok

            $attributes['death'] = $death;
            $attributes['langs'] = json_decode($attributes['langs']);
            $res = $this->put(route('author.create'), $attributes);

            $this->assertNotEquals(500, $res->status());

            $res->assertSessionHasNoErrors();

            $attributes['langs'] = json_encode($attributes['langs']);
            $this->assertDatabaseHas($authorTable, $attributes);
        }
    }
}
