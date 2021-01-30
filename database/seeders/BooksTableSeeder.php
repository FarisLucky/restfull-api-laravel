<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::all()->each(function (Author $author){
            $book = Book::factory()->count(2)->create();
            $author->books()->sync($book->pluck("id"));
        });
    }
}
