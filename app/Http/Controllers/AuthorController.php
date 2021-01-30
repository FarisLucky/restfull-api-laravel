<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Author;
use App\Services\JSONAPIService;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class AuthorController extends Controller
{

    private $service;

    /**
     * AuthorController constructor.
     * @param JSONAPIService $service
     */
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JSONAPICollection
     */
    public function index()
    {
        return $this->service->fetchResourcesIndex(Author::class, 'authors');
    }

    /**
     * @param CreateAuthorRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAuthorRequest $request)
    {
        return $this->service->createResource(Author::class, $request->input('data.attributes'));
    }

    /**
     * @param int $author
     * @return JSONAPIResource
     */
    public function show(int $author)
    {
        return $this->service->fetchResource(Author::class, $author, 'authors');
    }

    /**
     * @param UpdateAuthorRequest $request
     * @param Author $author
     * @return JSONAPIResource
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        return $this->service->updateResource($author, $request->input('data.attributes'));
    }

    /**
     * @param Author $author
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Author $author)
    {
        return $this->service->deleteResource($author);
    }
}
