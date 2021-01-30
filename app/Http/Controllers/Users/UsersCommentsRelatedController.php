<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorsCollection;
use App\Http\Resources\JSONAPICollection;
use App\Models\User;
use App\Services\JSONAPIService;

class UsersCommentsRelatedController extends Controller
{

    private $service;

    /**
     * BookAuthorsRelatedController constructor.
     * @param JSONAPIService $service
     */
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }


    /**
     * @param User $user
     * @return JSONAPICollection
     */
    public function index(User $user)
    {
        return $this->service->fetchRelated($user, 'comments');
    }
}
