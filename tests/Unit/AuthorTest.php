<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Collection;

class AuthorTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_methodical_insert()
    {

        $authorModel = new Author;
        $authorTable = $authorModel->getTable();
        $this->withoutExceptionHandling();

        $attributes = Author::factory()->create()->getAttributes();
        $this->assertDatabaseHas($authorTable, $attributes);
    }

    public function test_variable_insert()
    {

        $authorModel = new Author;
        $authorTable = $authorModel->getTable();
        $this->withoutExceptionHandling();
        // By Faker
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

        try {
            $record = Author::create($attributes);
        } catch(\Exception $e) {
            $this->assertTrue(FALSE);
        }
        $this->assertTrue(TRUE);
    }

    public function test_has_books()
    {
        $author = Author::factory()->create();

        $this->assertInstanceOf(Collection::class, $author->books);
    }
}
