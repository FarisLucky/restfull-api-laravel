<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorsCollection;
use App\Models\Book;
use Illuminate\Http\Request;

class BookAuthorsRelatedController extends Controller
{

    /**
     * Get Related Authors
     *
     * @param Book $book
     * @return AuthorsCollection
     */
    public function index(Book $book)
    {
        return new AuthorsCollection($book->authors);
    }
}
