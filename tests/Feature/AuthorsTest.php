<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Author;

class AuthorsTest extends TestCase
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

    public function test_create_new()
    {
        $authorModel = new Author;
        $authorTable = $authorModel->getTable();

        $attributes = [
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName(),
            'origin' => $this->faker->country(),
            'langs' => json_encode([$this->faker->languageCode()]),
            'birth' => $this->faker->date('Y-m-d'),
            'death' => $this->faker->optional(0.5, NULL)->date('Y-m-d', 'now'),
        ];

        //Assert that proper use will return ok

        $res = $this->put('/authors', $attributes);

        $this->assertNotEquals(500, $res->status());

        $res->assertRedirect('/authors');

        $this->assertDatabaseHas($authorTable, $attributes);
    }
}
