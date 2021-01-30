<?php

namespace App\Http\Controllers\Authors;

use App\Http\Controllers\Controller;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Models\Author;
use App\Services\JSONAPIService;

class AuthorsBooksController extends Controller
{
    private $service;

    /**
     * AuthorsBooksController constructor.
     * @param $service
     */
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Author $author
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Author $author)
    {
        return $this->service->fetchRelationship($author, "books");
    }

    /**
     * @param JSONAPIRelationshipRequest $request
     * @param Author $author
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(JSONAPIRelationshipRequest $request, Author $author)
    {
        return $this->service->updateManyToManyRelationships($author, "books", $request->input("data.*.id"));
    }

}
