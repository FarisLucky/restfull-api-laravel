<?php

namespace Database\Seeders;

use App\Models\Author;
use Database\Factories\AuthorFactory;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::factory()->count(50)->create();
    }
}
