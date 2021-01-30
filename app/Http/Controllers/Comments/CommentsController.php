<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\JSONAPIRequest;
use App\Models\Comment;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * @var JSONAPIService
     */
    private $service;

    /**
     * CommentsController constructor.
     * @param $service
     */
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }


    /**
     * @return \App\Http\Resources\JSONAPICollection
     */
    public function index()
    {
        return $this->service->fetchResourcesIndex(Comment::class, "comments");
    }

    /**
     * @param JSONAPIRequest $request
     */
    public function store(JSONAPIRequest $request)
    {
        return $this->service->createResource(Comment::class,
            $request->input('data.attributes'), $request->input('data.relationships'));
    }

    /**
     * @param $id
     * @return \App\Http\Resources\JSONAPIResource
     */
    public function show($id)
    {
        return $this->service->fetchResource(Comment::class,$id,"comments");
    }

    /**
     * @param JSONAPIRequest $request
     * @param $id
     * @return \App\Http\Resources\JSONAPIResource
     */
    public function update(JSONAPIRequest $request, Comment $comment)
    {
        return $this->service->updateResource($comment, $request->input("data.attributes"));
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Comment $comment)
    {
        return $this->service->deleteResource($comment);
    }
}
