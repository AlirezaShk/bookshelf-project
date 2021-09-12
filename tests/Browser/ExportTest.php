<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Book;

class ExportTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        Book::factory(10)->create();
        $this->browse(function (Browser $browser) {
            $browser->visit(route('book.list'))
                    ->assertSee('Select an output file type')
                    ->select('#exportTypeSelector')
                    ->press('export_submit')
                    ->assertDontSee('error');

        });
    }
}
