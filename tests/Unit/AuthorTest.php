<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

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
        $attributes = [
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName(),
            'origin' => $this->faker->country(),
            'langs' => json_encode([$this->faker->languageCode()]),
            'birth' => $this->faker->date('Y-m-d'),
            'death' => $this->faker->optional(0.5, NULL)->date('Y-m-d', 'now'),
        ];

        $record = Author::create($attributes);

        $this->assertDatabaseHas($authorTable, $attributes);
    }

}
