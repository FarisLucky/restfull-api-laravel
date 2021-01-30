<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\JSONAPIService;

class CommentsBooksRelatedController extends Controller
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
     * @return \App\Http\Resources\JSONAPICollection|\App\Http\Resources\JSONAPIResource
     */
    public function index(Comment $comment)
    {
        return $this->service->fetchRelated($comment, "books");
    }
}
