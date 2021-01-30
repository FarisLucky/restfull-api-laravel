<?php

namespace App\Http\Controllers;

use App\Http\Requests\BooksAuthorsRelationshipsRequest;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Models\Book;
use App\Services\JSONAPIService;

class BooksAuthorsRelationshipsController extends Controller
{
    private $service;

    /**
     * BooksAuthorsRelationshipsController constructor.
     * @param JSONAPIService $service
     */
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }


    /**
     * @param Book $book
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Book $book)
    {
        return $this->service->fetchRelationship($book, 'authors');
    }

    /**
     * @param BooksAuthorsRelationshipsRequest $request
     * @param Book $book
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(JSONAPIRelationshipRequest $request, Book $book)
    {
        return $this->service->updateManyToManyRelationships($book, 'authors', $request->input('data.*.id'));
    }
}
