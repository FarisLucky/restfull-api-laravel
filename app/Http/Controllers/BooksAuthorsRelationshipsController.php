<?php

namespace App\Http\Controllers;

use App\Http\Requests\BooksAuthorsRelationshipsRequest;
use App\Http\Resources\AuthorsIdentifierResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksAuthorsRelationshipsController extends Controller
{

    /**
     * @param Book $book
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Book $book)
    {
        return AuthorsIdentifierResource::collection($book->authors);
    }

    /**
     * @param BooksAuthorsRelationshipsRequest $request
     * @param Book $book
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(BooksAuthorsRelationshipsRequest $request, Book $book)
    {
        $ids = $request->input('data.*.id');
        $book->authors()->sync($ids);
        return response(null, 204);
    }
}
