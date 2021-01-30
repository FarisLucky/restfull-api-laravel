<?php

namespace App\Http\Controllers\Authors;

use App\Http\Controllers\Controller;
use App\Http\Requests\JSONAPIRequest;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Author;
use App\Services\JSONAPIService;
use Illuminate\Http\JsonResponse;

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
     * @param JSONAPIRequest $request
     * @return JsonResponse
     */
    public function store(JSONAPIRequest $request)
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
     * @param JSONAPIRequest $request
     * @param Author $author
     * @return JSONAPIResource
     */
    public function update(JSONAPIRequest $request, Author $author)
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
