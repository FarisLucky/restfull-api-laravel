<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Models\Book;
use App\Services\JSONAPIService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

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
     * @param JSONAPIRelationshipRequest $request
     * @param Book $book
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function update(JSONAPIRelationshipRequest $request, Book $book)
    {
        if (Gate::denies('admin-only')) {
            throw new AuthorizationException("Anda tidak memiliki akses ke aksi ini");
        }
        return $this->service->updateManyToManyRelationships($book, 'authors', $request->input('data.*.id'));
    }
}
