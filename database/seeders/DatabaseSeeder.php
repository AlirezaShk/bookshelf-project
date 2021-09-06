<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(BookSeeder::class);

        // It's currently Unnecessary to call the below function.
        
        // $this->call(AuthorSeeder::class);
    }
}
