<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Models\Comment;
use App\Services\JSONAPIService;

class CommentsUsersRelationshipsController extends Controller
{
    /**
     * @var JSONAPIService
     */
    private $service;

    /**
     * CommentsBooksRelationshipsController constructor.
     * @param $service
     */
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Comment $comment)
    {
        return $this->service->fetchRelationship($comment, "users");
    }

    /**
     * @param JSONAPIRelationshipRequest $request
     * @param Comment $comment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(JSONAPIRelationshipRequest $request, Comment $comment)
    {
        return $this->service->updateToOneRelationship($comment, 'users', $request->input('data.id'));
    }
}
