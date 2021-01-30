<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class CommentsUsersRelatedController extends Controller
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
     * @return \App\Http\Resources\JSONAPICollection
     */
    public function index(Comment $comment)
    {
        return $this->service->fetchRelated($comment, "users");
    }

}
